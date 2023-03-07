### 1. Installation
`composer require sabih-od/chat-module-laravel`

`php artisan vendor:publish --provider="ChatModule\ChatModuleServiceProvider" --tag=migrations`

`php artisan vendor:publish --provider="ChatModule\ChatModuleServiceProvider" --tag=config`

`php artisan vendor:publish --provider="ChatModule\ChatModuleServiceProvider" --tag=assets`

### 2. Set websocket

`BROADCAST_DRIVER=pusher`

`PUSHER_APP_ID=3e176ea8ddd4`

`PUSHER_APP_KEY=09f7337de6e2`

`PUSHER_APP_SECRET=f15db97569e8`

`PUSHER_APP_CLUSTER=mt1`