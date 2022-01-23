<?php
/**
 * Plugin Name: Nautes Test rest-api
 */
add_action('rest_api_init', function () {
  register_rest_route( 'test/v1', 'latest-posts/(?P<category_id>\d+)',array(
                'methods'  => 'GET',
                'callback' => 'get_latest_posts_by_category'
      ));
  register_rest_route( 'test/v1', 'authors',array(
                'methods'  => 'GET',
                'callback' => 'get_all_authors'
      ));
  register_rest_route( 'test/v1', 'authors-email',array(
                'methods'  => 'GET',
                'callback' => 'get_all_authors_custom_obj'
      ));
});


function get_latest_posts_by_category($request) {

    $args = array(
            'category' => $request['category_id']
    );

    $posts = get_posts($args);
    if (empty($posts)) {
    return new WP_Error( 'empty_category', 'there is no post in this category', array('status' => 404) );

    }

    $response = new WP_REST_Response($posts);
    $response->set_status(200);

    return $response;
}

function get_all_authors() {

    $users = get_users();
    if (empty($users)) {
    return new WP_Error( 'no_users', 'currently there are no users registred on this site', array('status' => 404) );

    }
    $response = new WP_REST_Response($users);
    $response->set_status(200);

    return $response;
}
function get_all_authors_custom_obj() {

    class clean_user_response  {};

    $clean_users = [];
    $users = get_users();
    if (empty($users)) {
    return new WP_Error( 'no_users', 'currently there are no users registred on this site', array('status' => 404) );

    }

    foreach($users as $user) {
        $tmp_user = new clean_user_response;
        $tmp_user->email = $user->data->user_email;
        $tmp_user->foo_element = "Stringa forzata";
        
        //$tmp_user["Campo con spazi"] = "Stringa forzata";
        array_push($clean_users,$tmp_user);
        
    }

    $response = new WP_REST_Response($clean_users);
    $response->set_status(200);

    return $response;
}