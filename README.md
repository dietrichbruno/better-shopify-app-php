## Description

This project is based on the original laravel base template from Shopify. 
I have made a lot of quality of life improvements regarding dockerfiles, docker-compose and bootstrap of project environment stuff. For example, makefile for starting reactive frontend, ngrok tunnel, up of docker containers. Lots of useful bash scripts, mysql, redis and nginx. 

## Tech Stack

This project combines a number of third party open source tools:

-   [Laravel](https://laravel.com/) builds and tests the backend.
-   [Vite](https://vitejs.dev/) builds the [React](https://reactjs.org/) frontend.
-   [React Router](https://reactrouter.com/) is used for routing. We wrap this with file-based routing.
-   [React Query](https://react-query.tanstack.com/) queries the Admin API.

These third party tools are complemented by Shopify specific tools to ease app development:

-   [Shopify API library](https://github.com/Shopify/shopify-api-php) adds OAuth to the Laravel backend. This lets users install the app and grant scope permissions.
-   [App Bridge React](https://shopify.dev/tools/app-bridge/react-components) adds authentication to API requests in the frontend and renders components outside of the embedded App’s iFrame.
-   [Polaris React](https://polaris.shopify.com/) is a powerful design system and component library that helps developers build high quality, consistent experiences for Shopify merchants.
-   [Custom hooks](https://github.com/Shopify/shopify-frontend-template-react/tree/main/hooks) make authenticated requests to the GraphQL Admin API.
-   [File-based routing](https://github.com/Shopify/shopify-frontend-template-react/blob/main/Routes.jsx) makes creating new pages easier.

### Setting up your Laravel app

1. Run make first-install command:

    make first-install

2. Start vite server

    make front

3. Start ngrok tunnel

    make ngrok

4. Run make url to update the redirect urls with ngrok domains. The command dynamically fetches the urls from your ngrok tunnel and displays on the screen the urls you should use in the prompts.
Import: Whenever ngrok reboots, you have to run make url to update the dynamic address of ngrok

    make url

5. Access Shopify Partners, select your app and choose a dev store to install the app
