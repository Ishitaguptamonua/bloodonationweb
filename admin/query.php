<?php
session_start(); // Start the session

include 'conn.php';
include 'session.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) 
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <style>
    #sidebar {
      position: relative;
      margin-top: -20px;
    }

    #content {
      position: relative;
      margin-left: 210px;
    }

    @media screen and (max-width: 600px) {
      #content {
        margin-left: auto;
        margin-right: auto;
      }
    }

    #he {
      font-size: 14px;
      font-weight: 600;
      text-transform: uppercase;
      padding: 3px 7px;
      color: #fff;
      text-decoration: none;
      border-radius: 3px;
      text-align: center;
    }

    .table th, .table td {
      text-align: center;
    }

    .pagination {
      margin-top: 20px;
      display: flex;
      justify-content: center;
    }
  </style>
</head>

<body style="color:black">
  <div id="header">
    <?php include 'header.php'; ?>
  </div>

  <div id="sidebar">
    <?php $active = "query"; include 'sidebar.php'; ?>
  </div>

  <div id="content">
    <div class="content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 lg-12 sm-12">
            <h1 class="page-title">User Query</h1>
          </div>
        </div>
        <hr>

        <?php
        include 'conn.php';

        // Pagination setup
        $limit = 10;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $count = $offset + 1;

        $sql = "SELECT * FROM contact_query LIMIT {$offset}, {$limit}";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
        ?>

          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Mobile Number</th>
                  <th>Message</th>
                  <th>Posting Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) { ?>
                  <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo $row['query_name']; ?></td>
                    <td><?php echo $row['query_mail']; ?></td>
                    <td><?php echo $row['query_number']; ?></td>
                    <td><?php echo $row['query_message']; ?></td>
                    <td><?php echo $row['query_date']; ?></td>
                    <td>
                      <?php if ($row['query_status'] == 1) { ?>
                        <span class="label label-success">Read</span>
                      <?php } else { ?>
                        <a href="update_status.php?id=<?php echo $row['query_id']; ?>" 
                           class="btn btn-warning btn-sm"
                           onclick="return confirm('Mark this query as read?')">Mark as Read</a>
                      <?php } ?>
                    </td>
                    <td id="he">
                      <a href="delete_query.php?id=<?php echo $row['query_id']; ?>" 
                         class="btn btn-danger btn-sm"
                         onclick="return confirm('Are you sure you want to delete this query?')">Delete</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="pagination">
            <?php
            $sql1 = "SELECT COUNT(*) AS total FROM contact_query";
            $result1 = mysqli_query($conn, $sql1);
            $row1 = mysqli_fetch_assoc($result1);
            $total_records = $row1['total'];
            $total_pages = ceil($total_records / $limit);

            if ($page > 1) {
              echo '<a href="?page=' . ($page - 1) . '" class="btn btn-default">Prev</a>';
            }
            for ($i = 1; $i <= $total_pages; $i++) {
              $active = ($i == $page) ? 'btn-primary' : 'btn-default';
              echo '<a href="?page=' . $i . '" class="btn ' . $active . '">' . $i . '</a>';
            }
            if ($page < $total_pages) {
              echo '<a href="?page=' . ($page + 1) . '" class="btn btn-default">Next</a>';
            }
            ?>
          </div>

        <?php
        } else {
          echo '<div class="alert alert-warning text-center">No records found.</div>';
        }
        ?>

      </div>
    </div>
  </div>

  <?php
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    echo '<div class="alert alert-danger"><b>Please Login First To Access Admin Portal.</b></div>';
    echo '<form method="post" action="login.php" class="form-horizontal">';
    echo '<div class="form-group">';
    echo '<div class="col-sm-8 col-sm-offset-4">';
    echo '<button class="btn btn-primary" type="submit">Go to Login Page</button>';
    echo '</div>';
    echo '</div>';
    echo '</form>';
  }
  ?>
</body>
</html>
