<?php
session_start();

if ( !isset( $_SESSION[ 'user_id' ] ) || !isset( $_SESSION[ 'username' ] ) ) {
    header( 'Location: ../login.php?error=Please log in to create a post' );
    exit;
}

if ( isset( $_POST[ 'title' ] ) && isset( $_POST[ 'text' ] ) && isset( $_POST[ 'category' ] ) ) {
    include '../db_conn.php';

    $title = trim( $_POST[ 'title' ] );
    $text = trim( $_POST[ 'text' ] );
    $category = $_POST[ 'category' ];

    if ( empty( $title ) ) {
        $em = 'Title is required';
        header( "Location: ../post-add.php?error=$em" );
        exit;
    }

    if ( empty( $text ) ) {
        $em = 'Content is required';
        header( "Location: ../post-add.php?error=$em" );
        exit;
    }

    if ( empty( $category ) ) {
        $category = 0;
    }

    $image_name = $_FILES[ 'cover' ][ 'name' ] ?? '';
    $res = false;

    if ( $image_name !== '' ) {
        $image_size = $_FILES[ 'cover' ][ 'size' ];
        $image_temp = $_FILES[ 'cover' ][ 'tmp_name' ];
        $error = $_FILES[ 'cover' ][ 'error' ];

        if ( $error === 0 ) {
            if ( $image_size > 130000 ) {
                $em = 'Sorry, your file is too large.';
                header( "Location: ../post-add.php?error=$em" );
                exit;
            }

            $image_ex = strtolower( pathinfo( $image_name, PATHINFO_EXTENSION ) );
            $allowed_exs = [ 'jpg', 'jpeg', 'png' ];

            if ( in_array( $image_ex, $allowed_exs ) ) {
                $new_image_name = uniqid( 'COVER-', true ) . '.' . $image_ex;
                $image_path = '../upload/blog/' . $new_image_name;
                move_uploaded_file( $image_temp, $image_path );

                $sql = 'INSERT INTO post(post_title, post_text, category, cover_url) VALUES (?, ?, ?, ?)';
                $stmt = $conn->prepare( $sql );
                $res = $stmt->execute( [ $title, $text, $category, $new_image_name ] );
            } else {
                $em = "You can't upload files of this type";
                header( "Location: ../post-add.php?error=$em" );
                exit;
            }
        } else {
            $em = 'File upload error';
            header( "Location: ../post-add.php?error=$em" );
            exit;
        }
    } else {
        $sql = 'INSERT INTO post(post_title, post_text, category) VALUES (?, ?, ?)';
        $stmt = $conn->prepare( $sql );
        $res = $stmt->execute( [ $title, $text, $category ] );
    }

    if ( $res ) {
        $sm = 'Successfully Created!';
        header( "Location: ../post-add.php?success=$sm" );
        exit;
    }

    $em = 'Unknown error occurred';
    header( "Location: ../post-add.php?error=$em" );
    exit;
}

header( 'Location: ../post-add.php' );
exit;
