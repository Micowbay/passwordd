<?php
$servername = "DESKTOP-UB454E1"; // 数据库服务器地址
$username = "sa"; // 数据库用户名
$password = "123456"; // 数据库密码
$dbname = "SQLinject"; // 数据库名

// 获取用户提交的用户名和密码
if(isset($_POST['username']) && isset($_POST['password'])){
    $user_username = $_POST['username'];
    $user_password = $_POST['password'];

    // 设置连接信息
    $connectionInfo = array(
        "Database" => $dbname,
        "UID" => $username,
        "PWD" => $password
    );

    // 建立连接
    $conn = sqlsrv_connect($servername, $connectionInfo);

    if($conn){
        // 查询数据库中是否存在匹配的用户名和密码
        // 不使用参数化查询，直接将输入嵌入SQL语句
        $query = "SELECT * FROM users WHERE username = '$user_username' AND password = '$user_password'";
        
        // 显示生成的查询语句
        //echo "Generated Query: " . $query . "<br>";

        $result = sqlsrv_query($conn, $query);

        if($result === false){
            // 查询出错
            die(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            // 用户名和密码匹配
            echo '<div style="background-color: yellow;font-size: 2em; text-align: center;">Login successful!</div>';
            
            if($user_username === "'OR 1=1 --"){
                //echo '<br><img src="1equal1.PNG" alt="Special Image">'. "<br>";
                echo '<br><button onclick="showImage()">查看在 SQL Server 中執行</button>';
            }
        } else {
            // 用户名和密码不匹配
            echo '<div style="background-color: black; color: white;font-size: 2em; text-align: center;">Invalid username or password.</div>';
        }
         // 显示生成的查询语句
         echo "Generated Query: " . $query . "<br>";

         echo '<script>
         function showImage() {
             var img = new Image();
             img.src = "1equal1.PNG";
             img.alt = "Special Image";
             document.body.appendChild(img);
         }
         </script>';
        

        // 释放结果集
        sqlsrv_free_stmt($result);

        // 关闭数据库连接
        sqlsrv_close($conn);
    }else{
        // 连接数据库失败
        echo "Connection could not be established.";
    }
} else {
    echo "Username and password must be provided.";
}
?>
