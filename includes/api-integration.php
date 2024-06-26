<?php
function wc_pde_generate_description($product_data) {
    $options = get_option('wc_pde_options');
    $api_key = $options['api_key'];
    $api_url = 'https://api.openai.com/chat/completions/v1/'; // API endpoint, compatible with pyChatGPTAPI+ endpoint...

    $body = array(
        'model' => 'gpt-3.5-turbo',
        'messages' => array(
            array(
                'role' => 'system',
                'content' => 'You are a helpful assistant, skilled in creating engaging product description seo optimized wrritten in french.'
            ),
            array(
                'role' => 'user',
                'content' => "Please create a product description using these details: " . $product_data
            )
        )
    );

    $response = wp_remote_post($api_url, array(
        'method' => 'POST',
//	'timeout' => 80, // uncomment if you are using the self hosted pyChatGPTAPI+ project
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ),
        'body' => json_encode($body),
        'data_format' => 'body',
    ));

    if (is_wp_error($response)) {
        // Handle error; return default description or log for further investigation
        return 'Error contacting OpenAI API: ' . $response->get_error_message();
    }

    $api_response = json_decode(wp_remote_retrieve_body($response), true);

    // Check if response contains the expected output and handle accordingly
    if (!empty($api_response['choices'][0]['message']['content'])) {
        return strip_tags($api_response['choices'][0]['message']['content']);
    } else {
        // Handle unexpected response; return default description or log for further investigation
        return 'Received unexpected response from OpenAI API.';
    }
}
