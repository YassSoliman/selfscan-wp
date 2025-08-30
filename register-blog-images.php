<?php
/**
 * Temporary script to register blog images in WordPress media library
 * Run this via browser: http://localhost:10008/wp-content/themes/selfscan/register-blog-images.php
 */

// Load WordPress
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

if (!is_user_logged_in() || !current_user_can('upload_files')) {
    die('You need to be logged in as an administrator to run this script.');
}

// Images to register
$images = [
    [
        'file' => '/wp-content/uploads/2025/08/blog-hero-decor.jpg',
        'title' => 'Fingerprint Background Check Technology',
        'alt' => 'Digital fingerprint scanning technology for background checks',
        'description' => 'Hero image showing fingerprint scanning technology used in background checks'
    ],
    [
        'file' => '/wp-content/uploads/2025/08/blog-article-01.jpg',
        'title' => 'Background Checks in Canadian Hiring',
        'alt' => 'Employer reviewing candidate background check',
        'description' => 'Illustration of employer conducting background checks in hiring process'
    ],
    [
        'file' => '/wp-content/uploads/2025/08/blog-article-02.jpg',
        'title' => 'Professional Background Screening',
        'alt' => 'Professional reviewing background check documentation',
        'description' => 'Professional person reviewing background screening documentation'
    ],
    [
        'file' => '/wp-content/uploads/2025/08/blog-article-03.jpg',
        'title' => 'Successful Business Professional',
        'alt' => 'Business professional reviewing hiring documents',
        'description' => 'Professional business person managing successful hiring process'
    ]
];

$results = [];

foreach ($images as $image_data) {
    $file_path = ABSPATH . ltrim($image_data['file'], '/');
    
    if (!file_exists($file_path)) {
        $results[] = "Error: File not found - " . $file_path;
        continue;
    }
    
    // Get file type
    $file_type = wp_check_filetype(basename($file_path), null);
    
    // Prepare attachment data
    $attachment = array(
        'post_mime_type' => $file_type['type'],
        'post_title' => $image_data['title'],
        'post_content' => $image_data['description'],
        'post_status' => 'inherit'
    );
    
    // Insert the attachment
    $attach_id = wp_insert_attachment($attachment, $file_path);
    
    if (!is_wp_error($attach_id)) {
        // Generate metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
        wp_update_attachment_metadata($attach_id, $attach_data);
        
        // Set alt text
        update_post_meta($attach_id, '_wp_attachment_image_alt', $image_data['alt']);
        
        $results[] = "Successfully registered: " . $image_data['title'] . " (ID: $attach_id)";
    } else {
        $results[] = "Error registering: " . $image_data['title'] . " - " . $attach_id->get_error_message();
    }
}

// Output results
echo "<h2>Blog Image Registration Results</h2>";
echo "<ul>";
foreach ($results as $result) {
    echo "<li>" . esc_html($result) . "</li>";
}
echo "</ul>";

// Get the new attachment IDs for setting featured images
$hero_id = get_posts([
    'post_type' => 'attachment',
    'meta_query' => [
        [
            'key' => '_wp_attachment_image_alt',
            'value' => 'Digital fingerprint scanning technology for background checks',
            'compare' => '='
        ]
    ],
    'fields' => 'ids',
    'posts_per_page' => 1
]);

$article_01_id = get_posts([
    'post_type' => 'attachment',
    'meta_query' => [
        [
            'key' => '_wp_attachment_image_alt',
            'value' => 'Employer reviewing candidate background check',
            'compare' => '='
        ]
    ],
    'fields' => 'ids',
    'posts_per_page' => 1
]);

$article_02_id = get_posts([
    'post_type' => 'attachment',
    'meta_query' => [
        [
            'key' => '_wp_attachment_image_alt',
            'value' => 'Professional reviewing background check documentation',
            'compare' => '='
        ]
    ],
    'fields' => 'ids',
    'posts_per_page' => 1
]);

$article_03_id = get_posts([
    'post_type' => 'attachment',
    'meta_query' => [
        [
            'key' => '_wp_attachment_image_alt',
            'value' => 'Business professional reviewing hiring documents',
            'compare' => '='
        ]
    ],
    'fields' => 'ids',
    'posts_per_page' => 1
]);

echo "<h3>Setting Featured Images for Blog Posts</h3>";
echo "<ul>";

// Set featured images for blog posts
if (!empty($hero_id)) {
    set_post_thumbnail(314, $hero_id[0]);
    echo "<li>Set featured image for Post 314 (Fingerprint vs Name-Based) - Image ID: " . $hero_id[0] . "</li>";
}

if (!empty($article_01_id)) {
    set_post_thumbnail(315, $article_01_id[0]);
    echo "<li>Set featured image for Post 315 (Hiring Practices) - Image ID: " . $article_01_id[0] . "</li>";
}

if (!empty($article_02_id)) {
    set_post_thumbnail(316, $article_02_id[0]);
    echo "<li>Set featured image for Post 316 (Security Compliance) - Image ID: " . $article_02_id[0] . "</li>";
}

if (!empty($article_03_id)) {
    set_post_thumbnail(317, $article_03_id[0]);
    echo "<li>Set featured image for Post 317 (Customer Story) - Image ID: " . $article_03_id[0] . "</li>";
}

echo "</ul>";
echo "<p><strong>Done! You can now delete this file.</strong></p>";
?>