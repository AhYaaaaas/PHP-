<!-- /*
 * @Date: 2022-10-14 20:41:38
 * @LastEditors: AhYaaaaas xuanyige87@gmail.com
 * @LastEditTime: 2022-10-25 12:43:58
 * @FilePath: \undefinedd:\phpstudy_pro\WWW\php\admin.php
 */ -->
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
  $name = $_POST["name"];
  $author = $_POST['author'];
  $press = $_POST['press'];
  $price = $_POST['price'];
  $position = $_POST['position'];
  $conn = new mysqli(serverName, userName, password, dbName);
  $sql = "INSERT INTO book_info(book_name,book_author,book_press,book_price,book_position) values ('${name}','${author}','${press}',${price},'${position}')";
  $isError = $conn->query($sql);
  if($isError){
    $sql_id = "SELECT book_id FROM book_info WHERE book_name = '${name}' and book_author = '${author}' and book_press = '${press}' and book_price = ${price} and book_position = '${position}'";
    $res = $conn->query($sql_id);
    $row = $res->fetch_assoc();
    $id = $row['book_id'];
    $stock_num = 1;
    $sql_stock = "INSERT INTO book_stock values(${id},${stock_num})";
    $stock_add = $conn->query($sql_stock);
    if($stock_add){
      echo "添加成功";
    }
  }
?>