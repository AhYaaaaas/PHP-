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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    .book_table{
      position:relative;
      left:50%;
      transform:translateX(-50%);
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
    a{
      text-decoration:none;
      color:black;
    }
    .buttonGroup{
      display:inline-block;
      position:relative;
      left:50%;
      transform:translateX(-50%);
      margin-top:20px;
    }
    form{
      box-sizing:border-box;
      border:1px solid #ddd;
      display:none;
      position:fixed;
      left:50%;
      transform:translateX(-50%);
      margin-top:20px;
    }
    form div{
      margin:20px 20px;
    }
    form div input{
      outline:none;
    }
    .submit input{
      display:block;
      margin:0 auto;
    }
  </style>
</head>
<body>
  <table class="book_table">
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
      <?php
      $servername = "localhost";
      $username = "root";
      $password = "gexuanyi0";
      $dbname = "book_managment";
      // 创建连接
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
        echo "连接失败";
      }else{
        $sql = "SELECT * FROM book_info";
        $result_info = $conn->query($sql);
        while($row = $result_info->fetch_assoc()){
          $sql_stock = 'SELECT stock FROM book_stock WHERE book_id='.$row["book_id"];
          $stock = ($conn->query($sql_stock))->fetch_assoc();
          echo "<tr>".
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
      ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan=7>
              <button class='add_book'>添加书籍</button>
              <button class="managment_book">管理书籍</button>
            </td>
        </tr>
      </tfoot>
  </table>
  <form action="book.php" class="book_management_form" method="post">
    <div class="bookName">
      <label for="_input_book_name">书籍名字</label>
      <input type="text" id="_input_book_name" name = "_input_book_name">
    </div>
    <div class="bookId">
      <label for="_input_book_id">书籍编号</label>
      <input type="text" id="_input_book_id" name="_input_book_id">
    </div>
    <div class="studentName">
      <label for="_input_student_name">学生名字</label>
      <input type="text" id="_input_student_name" name="_input_student_name">
    </div>
    <div class="studentId">
      <label label for="_input_student_id">学生学号</label>
      <input type="text" id="_input_student_id" name="_input_student_id">
    </div>
    <div>
      <label for="select_function">选择操作</label>
      <select name="operation" id="select_function">
        <option value="lend">借书</option>
        <option value="back">还书</option>
        <option value="search">查书</option>
        <option value="selfInfo">个人借阅情况</option>
      </select>
    </div>
    <div class="submit">
      <input type="submit" value="提交">
      <button class="close" style="display:block;margin:20px auto">取消</button>
    </div>
  </form>
  <form action="admin.php" class="_admin" method="post">
    <div>
      <label for="book_name">书名&nbsp;&nbsp;&nbsp;</label>
      <input type="text" name="name">
    </div>
    <div>
      <label for="book_author">作者&nbsp;&nbsp;&nbsp;</label>
      <input type="text" name="author">
    </div>
    <div>
      <label for="book_press">出版社</label>
      <input type="text" name="press">
    </div>
    <div>
      <label for="book_price">价格&nbsp;&nbsp;&nbsp;</label>
      <input type="text" name="price">
    </div>
    <div>
      <label for="book_position">位置&nbsp;&nbsp;&nbsp;</label>
      <input type="text" name="position">
    </div>
    <div>
      <input type ="submit" style="display:block;margin:0 auto;"></input>
      <button class="add_close" style="display:block;margin:20px auto">取消</button>
    </div>
  </form>
</body>
<script>
  let oManagementForm = document.querySelector('.book_management_form'),
      oAddBookForm =  document.querySelector('._admin'),
      oCloseButton = document.querySelector('.book_management_form .submit .close'),
      oManagementButton = document.querySelector('.managment_book'),
      oCloseAddButton = document.querySelector('.add_close'),
      oAddBookButton = document.querySelector('.add_book');
  oCloseButton.addEventListener('click',(e)=>{
    e.preventDefault();
    oManagementForm.style.display = "none";
  })
  oManagementButton.addEventListener('click',()=>{
    oManagementForm.style.display = "inline-block";
  })
  oAddBookButton.addEventListener('click',()=>{
    oAddBookForm.style.display = "inline-block";
  })
  oCloseAddButton.addEventListener('click',(e)=>{
    e.preventDefault();
    oAddBookForm.style.display = "none";
  })
</script>
</html>
