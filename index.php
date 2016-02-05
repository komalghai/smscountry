<?

    session_start();

    require __DIR__.'/vendor/autoload.php';
    use phpish\shopify;

    require __DIR__.'/conf.php';

    $shopify = shopify\client(SHOPIFY_SHOP, SHOPIFY_APP_API_KEY, SHOPIFY_APP_PASSWORD, true);

    try
    {
        # Making an API request can throw an exception
        $customers = $shopify('POST /admin/webhooks.json?access_token='.<?php echo $_SESSION['oauth_token'] ?>, array(), array
        (
            'webook' => array 
            (
                "topic": "customers/create",
                "address": "https://smscountry.herokuapp.com/",
                "format": "json"
            )

        ));

        print_r($customers);
    }
    catch (shopify\ApiException $e)
    {
        # HTTP status code was >= 400 or response contained the key 'errors'
        echo $e;
        print_R($e->getRequest());
        print_R($e->getResponse());
    }
    catch (shopify\CurlException $e)
    {
        # cURL error
        echo $e;
        print_R($e->getRequest());
        print_R($e->getResponse());
    }

?>
