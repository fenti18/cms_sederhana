<?php
// Get statistics
$stmt = $pdo->query("SELECT COUNT(*) as total_posts FROM posts");
$total_posts = $stmt->fetch()['total_posts'];

$stmt = $pdo->query("SELECT COUNT(*) as total_categories FROM categories");
$total_categories = $stmt->fetch()['total_categories'];

$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$total_users = $stmt->fetch()['total_users'];

// Get recent posts
$stmt = $pdo->query("SELECT posts.*, categories.name as category_name 
                     FROM posts 
                     LEFT JOIN categories ON posts.category_id = categories.id 
                     ORDER BY posts.created_at DESC LIMIT 5");
$recent_posts = $stmt->fetchAll();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $total_posts; ?></h3>
                            <p>Total Posts</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <a href="index.php?page=posts" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $total_categories; ?></h3>
                            <p>Categories</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <a href="index.php?page=categories" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $total_users; ?></h3>
                            <p>Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="index.php?page=users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Posts</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_posts as $post): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                                        <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?></td>
                                        <td>
                                            <?php if ($post['status'] == 'published'): ?>
                                                <span class="badge badge-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 