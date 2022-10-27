<!-- #  我真诚地保证：
#  我自己独立地完成了整个程序从分析、设计到编码的所有工作。
#  如果在上述过程中，我遇到了什么困难而求教于人，那么，我将在程序实习报告中
#  详细地列举我所遇到的问题，以及别人给我的提示。
#  在此，我感谢 XXX, …, XXX对我的启发和帮助。下面的报告中，我还会具体地提到
#  他们在各个方法对我的帮助。
#  我的程序里中凡是引用到其他程序或文档之处，
#  例如教材、课堂笔记、网上的源代码以及其他参考书上的代码段,
#  我都已经在程序的注释里很清楚地注明了引用的出处。
#  我从未没抄袭过别人的程序，也没有盗用别人的程序，
#  不管是修改式的抄袭还是原封不动的抄袭。
#  我编写这个程序，从来没有想过要去破坏或妨碍其他计算机系统的正常运转。 
#  <葛轩一>  -->
<?php
  define("serverName","localhost");
  define("userName","root");
  define("password","gexuanyi0");
  define("dbName","book_managment");
  function lend($studentName,$studentId,$bookId,$bookName){
    if(!($studentName || $studentId || $bookId)){
      echo '缺少参数';
    }else{
      $conn = new mysqli(serverName, userName, password, dbName);
      $stock = ($conn->query("SELECT stock from book_stock WHERE book_id=".$bookId))->fetch_assoc();
      if($stock['stock'] == 0){
        echo "书籍剩余数量为0";
      }else{
        $bookName = ($conn->query("SELECT book_name from book_info WHERE book_id=".$bookId))->fetch_assoc();
        $date = date("Y-m-d H:i:s");
        $sql = "
        INSERT INTO book_lend_return(book_id,student_id,student_name,lend_time) 
        values(".$bookId.",".$studentId.",'".$studentName."','".$date."');";
        $result = $conn->query($sql);
        if($result){
          echo "借阅成功";
        }else{
          echo "Error";
        }
      }
      mysqli_close($conn);
    }
  }
  function back($studentId,$bookId){
    if(!($studentId || $bookId)){
      echo "缺少参数";
    }else{
      $conn = new mysqli(serverName, userName, password, dbName);
      $date = date("Y-m-d H:i:s");
      $sql = "UPDATE book_lend_return SET isReturn = 1,return_time = '".$date."' WHERE student_id = ".$studentId." and book_id=".$bookId;
      $result = $conn->query($sql);
      if($result){
        $stock = ($conn->query("SELECT stock from book_stock WHERE book_id=".$bookId))->fetch_assoc();
        $add = $conn->query("UPDATE book_stock SET stock = ".++$stock["stock"]." WHERE book_id = ".$bookId);
        if($add){
          echo "还书成功";
        }else{
          echo "Error";
        }
      }else{
        echo "Error";
      }
      mysqli_close($conn);
    }
  }
  function queryBook($bookName){
    $conn = new mysqli(serverName, userName, password, dbName);
    $s = '';
    if ($conn->connect_error) {
      echo "连接失败";
    }else{
      $sql = "SELECT * FROM book_info WHERE book_name = '".$bookName."'";
      $result_info = $conn->query($sql);
      $result_id = $conn->query("SELECT book_id FROM book_info WHERE book_name = '".$bookName."'");
      $book_id_array = $result_id->fetch_assoc();
      $book_id = $book_id_array["book_id"];
      while($row = $result_info->fetch_assoc()){
        $sql_stock = 'SELECT stock FROM book_stock WHERE book_id='.$row["book_id"];
        $stock = ($conn->query($sql_stock))->fetch_assoc();
        $s = $s."<tr>".
          "<td>".$row["book_id"]."</td>".
          "<td>".$row["book_name"]."</td>".
          "<td>".$row["book_author"]."</td>".
          "<td>".$row["book_press"]."</td>".
          "<td>".$row["book_price"]."</td>".
          "<td>".$row["book_position"]."</td>".
          "<td>".$stock['stock']."</td>".
        "</tr>";
      }
    }
    mysqli_close($conn);
    return $s;
  }
  function querySelfLend($studentId){
    $s = '';
    $conn = new mysqli(serverName, userName, password, dbName);
    $sql = "SELECT * FROM book_lend_return WHERE student_id = ".$studentId;
    $temp = $conn->query($sql);
    while($result = $temp->fetch_assoc()){
      $sql_info = "SELECT * FROM book_info WHERE book_id = ".$result['book_id'];
      $result_info = $conn->query($sql_info);
      $row = $result_info->fetch_assoc();  
      $result["isReturn"] = $result["isReturn"]==1?'是':'否';
      $s = $s."<tr>".
        "<td>".$result['student_name']."</td>".
        "<td>".$studentId."</td>".
        "<td>".$row["book_id"]."</td>".
        "<td>".$row["book_name"]."</td>".
        "<td>".$row["book_author"]."</td>".
        "<td>".$row["book_press"]."</td>".
        "<td>".$row["book_price"]."</td>".
        "<td>".$result["lend_time"]."</td>".
        "<td>".$result["return_time"]."</td>".
        "<td>".$result["isReturn"]."</td>".
      "</tr>";
    }
    mysqli_close($conn);
    return $s;
  }
  $input_book_name = $_POST["_input_book_name"];
  $input_book_id = $_POST["_input_book_id"];
  $input_student_name = $_POST["_input_student_name"];
  $input_student_id = $_POST["_input_student_id"];
  $operation = $_POST["operation"];
  switch($operation){
    case "lend":
      lend($input_student_name,$input_student_id,$input_book_id,$input_book_name);
      break;
    case 'back':
      back( $input_student_id, $input_book_id);
    case 'search':
      $res = queryBook($input_book_name);
      echo '<table class="book_table">
              <thead>
                <tr>
                  <td>序号</td>
                  <td>书名</td>
                  <td>作者</td>
                  <td>出版社</td>
                  <td>价格</td>
                  <td>位置</td>
                  <td>库存</td>
                </tr>
              </thead>
              <tbody>
              '.$res.'
              </tbody>
            </table>';
      break;
      case 'selfInfo':
        $res = querySelfLend($input_student_id);
        echo '<table class="book_table">
                <thead>
                  <tr>
                    <td>姓名</td>
                    <td>学号</td>
                    <td>书籍编号</td>
                    <td>书名</td>
                    <td>作者</td>
                    <td>出版社</td>
                    <td>价格</td>
                    <td>结束时间</td>
                    <td>还书时间</td>
                    <td>是否归还</td>
                  </tr>
                </thead>
                <tbody>
                '.$res.'
                </tbody>
              </table>';
    break;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    .book_table{
      margin:0 auto;
    }
    .book_table td{
      width:auto;
      height:50px;
      box-sizing:border-box;
      border:1px solid grey;
      text-align:center;
      line-height:50px;
    }
    .book_table tr:nth-child(even){
      background-color:#ddd;
    }
  </style>
</head>
<body>
  
</body>
</html>