<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5-1</title>
        <link rel="stylesheet" href="classlist.css">
    </head>
    <body>
        <!--php パート-->
        <!--日付の宣言-->
        <?php
        date_default_timezone_set('Asia/Tokyo');
        
        //データベース 作成
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        // $sql = "DROP TABLE tbtest;";
        // $stmt = $pdo->query($sql);
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "create_date TEXT,"
        . "password char(4)"
        .");";
        $stmt = $pdo->query($sql);
        ?>
        
        <?php
        //データ記入
        if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['password']) && empty($_POST['blank'])){
            //データベースに記入
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, create_date, password) VALUES (:name, :comment, :create_date, :password)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':create_date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':password', $pw, PDO::PARAM_STR);
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $date = date("Y/m/d h:i:s");
            $pw = $_POST['password'];
            $sql -> execute();
        }//if
        
        //データ削除
        if(!empty($_POST['delete_number']) && !empty($_POST['delete_password'])){
            //データベースの削除
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            //PW一致するかを確認する
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            $id = $_POST['delete_number'] ; // idがこの値のデータだけを抽出したい、とする
            $pw = $_POST['delete_password'];
            $sql = 'SELECT COUNT(*) FROM tbtest WHERE id=:id AND password=:password';
            $stmt = $pdo -> prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $pw, PDO::PARAM_STR);
            $stmt->execute();
            
            $count = $stmt -> fetchColumn();
            
            if($count == 1){
                $sql = 'delete from tbtest where id=:id AND password=:password'; //password=:password 前のpasswordはL44のINSERT INTO tbtestを指す
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':password', $pw, PDO::PARAM_STR);
                $stmt->execute();
            }
        }//if
        
        //データ編集
        //Step 1 編集番号記入して、編集したい内容を表示する
        if(!empty($_POST['edit_number']) && !empty($_POST['edit_password'])){
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            $id = $_POST['edit_number'] ; // idがこの値のデータだけを抽出したい、とする
            $pw = $_POST['edit_password'];
            
            $sql = 'SELECT COUNT(*) FROM tbtest WHERE id=:id AND password=:password';
            $stmt = $pdo -> prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $pw, PDO::PARAM_STR);
            $stmt->execute();
            
            $count2 = $stmt -> fetchColumn();
            
            if($count2 == 1){
                $sql = 'SELECT * FROM tbtest WHERE id=:id AND password=:password';
                $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                $stmt->bindParam(':password', $pw, PDO::PARAM_STR);
                $stmt->execute();                             // ←SQLを実行する。
                $results = $stmt->fetchAll(); 
                    foreach ($results as $row){
                        //$rowの中にはテーブルのカラム名が入る
                        $editNumber = $row['id'];
                        $editName = $row['name'];
                        $editComment = $row['comment'];
                        $editPassword = $row['password'];
                    }//foreach
            }
        }//if
        
        //Step 2 編集した内容の記入
        if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['password']) && !empty($_POST['blank'])){
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            $id = $_POST['blank']; //変更する投稿番号
            $name = $_POST['name'];
            $comment = $_POST['comment']; //変更したい名前、変更したいコメントは自分で決めること
            $pw = $_POST['password'];
            $edit_date = date("Y/m/d h:i:s");
            $sql = 'UPDATE tbtest SET name=:name,comment=:comment,create_date=:create_date,password=:password WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $pw, PDO::PARAM_STR);
            $stmt->bindParam(':create_date', $edit_date, PDO::PARAM_STR);
            $stmt->execute();
        }
        ?>
        
        <!--HTML　パート-->
        <div class="background">
        <h1>♥簡易掲示板♥</h1>
        <!--記入フォーム-->
        <form action="" name="form 1" method="post">
            <label for="name">名前：</label>
            <input type="text" id="name" name="name" placeholder="山田太郎" value="<?php if(!empty($editName)){echo $editName;} ?>"><br>
            <label for="comment">コメント：</label>
            <input type="text" id="comment" name="comment" placeholder="Hello" value="<?php if(!empty($editComment)){echo $editComment;} ?>"><br>
            <label for="password">パスワード：</label>
            <input type="text" id="password" name="password" placeholder="半角英数字4桁" maxlength="4"><br>
            <input type="hidden" name="blank" value="<?php if(!empty($editNumber)){echo $_POST['edit_number'];} ?>">
            <input type="submit" name="submit"><br>
            <br>
        </form>
        <!--削除フォーム-->
        <form action="" name="form 2" method="post">
            <label for="delNum">削除番号：</label>
            <input type="number" id="delNum" name="delete_number"><br>
            <label for="delPS">パスワード：</label>
            <input type="text" id="delPS" name="delete_password" placeholder="半角英数字4桁" maxlength="4"><br>
            <input type="submit" name="delete" value="削除"><br>
            <br>
        </form>
        <!--編集フォーム-->
        <form action="" name"form 3" method="post">
            <label for="editNum">編集番号：</label>
            <input type="number" id="editNum" name="edit_number"><br>
            <label for="editpw">パスワード：</label>
            <input type="text" id="editpw" name="edit_password" placeholder="半角英数字4桁" maxlength="4"><br>
            <input type="submit" name="edit" value="編集"><br>
            <br>
        </form>
        
        <!--提示文 -->
        <div class="echo">
        <?php
        //記入
        if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['password']) && empty($_POST['blank'])){//記入オッケー
            echo "ご記入ありがとうございました。";
        }elseif(!empty($_POST['name']) && !empty($_POST['comment']) && empty($_POST['password']) && empty($_POST['blank'])){//パスワード記入して
            echo "パスワードをご記入お願いします。";
        }elseif(!empty($_POST['name']) && empty($_POST['comment']) && !empty($_POST['password']) && empty($_POST['blank'])){//comment
            echo "コメントをご記入お願いします。";
        }elseif(empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['password']) && empty($_POST['blank'])){//name
            echo "名前をご記入お願いします。";
        }elseif(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['password']) && !empty($_POST['blank'])){//編集
            echo "編集できました！";
        }elseif(!empty($_POST['name']) && !empty($_POST['comment']) && empty($_POST['password']) && !empty($_POST['blank'])){//編集
            echo "パスワードをご記入お願いします。";
        }
        
        //削除
        if(!empty($_POST['delete_number']) && !empty($_POST['delete_password'])){
            if($count == 1){
                echo "削除できました。";
            }else{
                echo "パスワードを間違った";
            }
        }elseif(!empty($_POST['delete_number']) && empty($_POST['delete_password'])){
            echo "パスワードをご記入お願いします。";
        }
        
        //編集
        if(!empty($_POST['edit_number']) && !empty($_POST['edit_password'])){
            if($count2 == 1){
                echo "編集お願いします。";
            }else{
                echo "パスワードを間違った";
            }
        }elseif(!empty($_POST['edit_number']) && empty($_POST['edit_password'])){
            echo "パスワードをご記入お願いします。";
        }
        ?>
        </div>
        <br>
        
        <!--投稿一覧-->
        <span style="font-size: 30px; color: crimson；font-wight: bold">～投稿一覧～</span><br>
        <span>（番号, 名前, コメント）</span><br><br>
        <?php
        $dsn = 'mysql:dbname=tb240173db;host=localhost';
            $user = 'tb-240173';
            $password = 'WXe8DK4wD5';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].'<br>';
            echo "<hr>";
            }
        ?>
        </div>
    </body>
</html>