<?php

require_once("cPanelApi.php");


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $cpuser = "----"; // نام کاربری cPanel شما
    $cppass = "---"; // رمز عبور cPanel شما
    $cphost = "----"; // نام دامنه یا IP سرور cPanel شما

    $databasePrefix = "db_"; // پیشوند برای نام پایگاه داده

    if (isset($_GET["action"]) && $_GET["action"] === "create_database") {
        // درخواست ایجاد دیتابیس و کاربر

        // تولید یک نام رندوم برای پایگاه داده و کاربر
        $randomSuffix = bin2hex(random_bytes(5)); // تولید یک رشته تصادفی 10 کاراکتری
        $dbname = $databasePrefix . $randomSuffix;
        $dbuser = $userPrefix . $randomSuffix;
        $dbpass = "databasepassword"; // رمز عبور پایگاه داده - باید این را به صورت ایمن تولید کنید!

        // ایجاد نام دیتابیس و کاربر
        $cPanel = new cPanelApi($cphost, $cpuser, $cppass);
        $result_create_db = $cPanel->createDataBaseMySQL($dbname);

        if ($result_create_db) {
            $response = [
                "ok" => true,
                "result" => [
                    "dbname" => $dbname
                ]
            ];
        } else {
            $response = [
                "ok" => false,
                "error" => "Error creating database and user."
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    } elseif (isset($_GET["action"]) && $_GET["action"] === "delete_database") {
        // درخواست حذف دیتابیس

        // بررسی وجود اطلاعات دیتابیس برای حذف
        if (isset($_GET["dbname"])) {
            $dbname = $_GET["dbname"];

            // حذف دیتابیس و کاربر
            $cPanel = new cPanelApi($cphost, $cpuser, $cppass);
            $result_delete_db = $cPanel->deleteDataBaseMySQL($dbname);

            if ($result_delete_db) {
                $response = [
                    "ok" => true,
                    "message" => "Database deleted successfully."
                ];
            } else {
                $response = [
                    "ok" => false,
                    "error" => "Error deleting database and user."
                ];
            }
        } else {
            $response = [
                "ok" => false,
                "error" => "Database name and user are required for deletion."
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $response = [
            "ok" => false,
            "error" => "Invalid action."
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}


