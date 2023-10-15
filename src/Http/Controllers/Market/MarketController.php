<?php

namespace Helious\SeatBusaMarket\Http\Controllers\Market;

use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;
use GuzzleHttp\Client;
use Seat\Eveapi\Models\Assets\CorporationAsset;
use Illuminate\Support\Facades\Notification;
use Seat\Notifications\Models\NotificationGroup;
use Helious\SeatBusaMarket\Notifications\NewOrder;
use Helious\SeatBusaMarket\Models\MarketOrders;

class MarketController extends Controller
{
    /**
     * Pulls the corp assets for corp id 170892597(CRICE Corporation) and lists all the items in the CorpSAG7 hangar.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $corpAssets = CorporationAsset::where('corporation_id', 170892597)
        ->where('location_flag', 'CorpSAG7')
        ->whereHas('container', function ($query) {
            $query->where('location_flag', '!=', 'AssetSafety');
        })
        ->with('type', 'container', 'structure', 'station')
        ->get();
    

        return view('seat-busa-market::market.index', compact('corpAssets'));
    }

    /**
     * Creates a new order.
     *
     * @return \Illuminate\Http\Response
     */
    public function createOrder(Request $request)
    {
        $items = $request->items;
        $items = json_decode($items, true);

        $price = $request->janiceAppraisal;

        // map $items quantity and typename to $item so i can use it in body
        $items = array_map(function ($item) {
            return [
                'name' => $item['typeName'],
                'quantity' => $item['quantity'],
            ];
        }, $items);
        $itemString = '';
        foreach ($items as $item) {
            $itemString .= $item['name'] . ' x' . $item['quantity'] . "\n";
        }

        $client = new Client();
        $response = $client->post('https://janice.e-351.com/api/rest/v2/appraisal?market=2&persist=true&compactize=true&pricePercentage=1', [
            'headers' => [
                'accept' => 'application/json',
                'X-ApiKey' => config('seat-busa-market-custom.janice_api_key'),
                'Content-Type' => 'text/plain',
            ],
            'body' => $itemString,
        ]);

        // make the responce and stdClass
        $janiceAppraisal = json_decode($response->getBody()->getContents());
        $janiceLink = "https://janice.e-351.com/a/". $janiceAppraisal->code;

        // detect handlers setup for the current notification
        $handlers = config('notifications.alerts.seat_market_newOrder.handlers', []);

        // retrieve routing candidates for the current notification
        $routes = $this->getRoutingCandidates();

        // in case no routing candidates has been delivered, exit
        if ($routes->isEmpty())
            return;

        $newOrderMessage = [
            'user' => auth()->user()->name,
            'janiceLink' => $janiceLink,
            'items' => $items,
            'price' => $price,
        ];

        // attempt to enqueue a notification for each routing candidates
        $routes->each(function ($integration) use ($handlers, $newOrderMessage) {
            if (array_key_exists($integration->channel, $handlers)) {

                // extract handler from the list
                $handler = $handlers[$integration->channel];

                // enqueue the notification
                Notification::route($integration->channel, $integration->route)
                    ->notify(new $handler($newOrderMessage));
            }
        });

        // create a new order
        $order = new MarketOrders;
        $order->user_id = auth()->user()->id;
        $order->order_json = json_encode($items);
        $order->estimated_price = $price;
        $order->janice_link = $janiceLink;
        $order->save();

        return redirect()->route('seat-busa-market.index')->with('success', 'Order Created, janice link: ' . $janiceLink );

    }

    /**
     * Shows the total orders that have been placed and their status.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders()
    {
        $orders = MarketOrders::orderBy('created_at', 'desc')->get();

        return view('seat-busa-market::market.orders', compact('orders'));
    }

    /**
     * Return the view to the order.
     *
     * @return \Illuminate\Http\Response
     */
    public function order($id)
    {
        $order = MarketOrders::findOrFail($id);

        return view('seat-busa-market::market.order', compact('order'));
    }

    /**
     * Update order to completed.
     *
     * @return \Illuminate\Http\Response
     */
    public function completeOrder($id)
    {

        $order = MarketOrders::where('id', $id)->first();
        $order->status = 'Completed';
        $order->save();

        return redirect()->route('seat-busa-market.order', ['id' => $id])->with('success', 'Order Completed');
    }

    private function getRoutingCandidates()
    {
        $settings = NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'seat_market_newOrder');
            })->get();

        $routes = $settings->map(function ($group) {
            return $group->integrations->map(function ($channel) {

                // extract the route value from settings field
                $settings = (array) $channel->settings;
                $key = array_key_first($settings);
                $route = $settings[$key];

                // build a composite object built with channel and route
                return (object) [
                    'channel' => $channel->type,
                    'route' => $route,
                ];
            });
        });

        return $routes->flatten()->unique(function ($integration) {
            return $integration->channel . $integration->route;
        });
    }

}
