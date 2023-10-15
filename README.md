# SeAT-BUSA-MART - Market plugin For Blackwater USA Inc.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/helious/seat-busa-market.svg?style=flat-square)](https://packagist.org/packages/helious/seat-busa-market)
[![Total Downloads](https://img.shields.io/packagist/dt/helious/seat-busa-market.svg?style=flat-square)](https://packagist.org/packages/helious/seat-busa-market)

![https://i.imgur.com/GCn4Xwa.jpg](https://i.imgur.com/GCn4Xwa.jpg)

## Installation

You can install the package via composer:

```bash
composer require helious/seat-busa-market
```

## Permissions
- Remember to give Roles the `Access Market` role under Seat-busa-market
- `Access Orders` lets you view all existing orders.

## Notifications
1. Create A `Integrations` with the your Slack/Discord webhook.
2. Go To Sidebar > `Notifications` > `Notification Groups`
3. Create a Group Name.
4. Edit the Group we created.
5. Add Your Slack/Discord webhook .
6. Alerts To `Seat BUSA MART - New Order`.
7. Add and your all set!.

## Janice
This plugin uses Janice by Mooncake Industrial to appraise orders and wont work without it. obtain a key from [here](https://discord.gg/7McHR3r) from `Eris Kirke`. once you have it add `JANICE_API_KEY` to your .env with the API key.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
