<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'app' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */

            'credentials' => [
                "type"=> "service_account",
              "project_id"=> "comprobador-de-loteria",
              "private_key_id"=> "593ab091e1b53dfc29afba30d8eb3a6789a9538d",
              "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC5e7TvNapb/7jn\npMp4Ew/PMuaWC5ku5ftKzasaZkE2Dt7nBnOl0mUvzZDWhdZUBalrDjpfxfMDcTIt\nVp1MpJf54qaZ6yxTqHQkgc9zBdR0w+xdt769P/7dVtaiabNuPUh9Lgk3VyXSkav0\ntNazSe0x/xZQwRicX4DB/+rkhIyEx/IXnUWsgyTKJ4CqUE6N+XSZK+OyR8JPgBjq\n0t4XDlMv0k3PAVdQQWCmQPiQkKrA7U86jxj9NkXepCAx3P/8TmdMXrWlMnjT6M+N\nhVQdZ3dKmFkKAbKNANWxg6+AI/iECXU+sMsEq67UJ2iBXKVN9+xDFaqVgI6ItOlF\nQutBUqN5AgMBAAECggEAD+3KmePDHRfRd9HkR2U6WCzCtlkJpIhAnpRvYMWDon0A\nHIP704jwLVJF4X5olm+kCdsUx/EhJQcIFvJXcOO5CiSHnTP1QGajjsJidToncYhP\n2LPfxV3lE89ngFbRKiLzgjiStwtyEUAhrdKylilqSCm5b2Gd156wro29TOGrNaMr\nu7huz8b247jjfK8Lz7FI7jsPYwsL9VcHrGIRP1PutWcbKWK5HGZULJ4fYCLbMNDp\nqxGvs+7NdFGgzZdZ2llQSUSJKRQeQC21LcozP7nnldv8p1BVms2NKdLIVVOl/tFH\n5u2G8BW1Roh5+V+JY2+fjaxIw41SB9CThOHcxgFWtwKBgQD5oEg6VkBJ4cxYdqbh\nDiVSxW+7DxwNRy1XmjBPFmvtIKAFDjeoRsifP8dJdCWyygdUbv2k7PBb5lZAfI3i\nxK8CsprYS+yEqxhDC9kepZhi0zz842iME5EKsLGb0NM4QwnAPaqEnYhOmZKB3TrU\nG81u3u1LT//x5D/FKPa0dYhGgwKBgQC+OCUxK90aocXNrq72R077nlnAyg5oJums\n6T46ECTyr29kGvu12q9dcsXyyBuFWSpC/yXUcxMwBQlQZ1zUnvNWgQdxt5zYriZT\nai1mrdil5tarqyLO7HZh4HHp1Uvx+JqLfS4v49pRPtxpUfwa+YVVGbCuD1FXUu1z\nP+cw9QptUwKBgQChAO5VIhhpDsgcLrtH4TXVgx6jigVP0x69p+eMuyt1UFulpxvi\n33S6sgo5qD5AaOn9XXEflOsaeyAai7uADdfLZ6NT6vRsRWZqqBOsHqaA5zoo6gwe\nfOhJ+tkzZKc3XJXHdn6q9mhaOdczB5vP/varkgF/V+P/AnbXFaq5ji0NPQKBgG5x\n4wZd1Y81BsiZ+HR+itmF488QgrTugxcmhj7oSMuwGRkioavBQbkcftqsXW5bizJD\nOw/TkPx8sB/DJB8/+0HqwQvB9IvK8e3U5G+N2RDaqyIZx6glm8bQrtN6SRJBqln7\nYOnlfklWGlv8gkiXQFddBzzJIA131O7p166RWHCJAoGBANbNDO3GORyKC7sSldsV\nMUAe5uYoOSxaLg0rwcvibWnOWv8mjcXQwtwTz3xLeeLvPevBZyzJEjgU7hMzmQWD\nqeGz35kfljelgjBh3HeN+0RIIdTXbuxruSYIliEAAmBHV8vIpJ1qE39QZd5r/qQB\ntMF3OzkfjDmahys83vrxfLOi\n-----END PRIVATE KEY-----\n",
              "client_email"=> "firebase-adminsdk-7d4bz@comprobador-de-loteria.iam.gserviceaccount.com",
              "client_id"=> "110752943550822667503",
              "auth_uri"=> "https=>//accounts.google.com/o/oauth2/auth",
              "token_uri"=> "https=>//oauth2.googleapis.com/token",
              "auth_provider_x509_cert_url"=> "https=>//www.googleapis.com/oauth2/v1/certs",
              "client_x509_cert_url"=> "https=>//www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-7d4bz%40comprobador-de-loteria.iam.gserviceaccount.com",
              "universe_domain"=> "googleapis.com"
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
