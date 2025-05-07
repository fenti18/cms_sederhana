<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get statistics
$stmt = $pdo->query("SELECT COUNT(*) FROM posts");
$total_posts = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM categories");
$total_categories = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();

// Get recent posts
$stmt = $pdo->query("
    SELECT p.*, c.name as category_name, u.username 
    FROM posts p 
    LEFT JOIN categories c ON p.category_id = c.id 
    LEFT JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC 
    LIMIT 5
");
$recent_posts = $stmt->fetchAll();
?>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content dashboard-bg">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1">
                        <i class="fas fa-newspaper"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Posts</span>
                        <span class="info-box-number"><?php echo $total_posts; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="fas fa-tags"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Categories</span>
                        <span class="info-box-number"><?php echo $total_categories; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-warning elevation-1">
                        <i class="fas fa-users"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Users</span>
                        <span class="info-box-number"><?php echo $total_users; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Posts -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clock mr-1"></i>
                            Recent Posts
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_posts as $post): ?>
                                    <tr>
                                        <td>
                                            <a href="?page=posts&action=edit&id=<?php echo $post['id']; ?>">
                                                <?php echo htmlspecialchars($post['title']); ?>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                                        <td><?php echo htmlspecialchars($post['username']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $post['status'] == 'published' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($post['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <a href="?page=posts" class="btn btn-sm btn-info float-right">
                            View All Posts
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-1"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="?page=posts&action=create" class="btn btn-primary btn-block">
                                    <i class="fas fa-plus mr-2"></i> New Post
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="?page=categories&action=create" class="btn btn-success btn-block">
                                    <i class="fas fa-folder-plus mr-2"></i> New Category
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="?page=users&action=create" class="btn btn-warning btn-block">
                                    <i class="fas fa-user-plus mr-2"></i> New User
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="?page=posts" class="btn btn-info btn-block">
                                    <i class="fas fa-list mr-2"></i> View All Posts
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
:root {
    --pastel-green: #98D8AA;
    --light-cream: #F7E1D7;
    --soft-green: #A8E6CF;
    --warm-cream: #FFD3B6;
    --dark-green: #4A8B6F;
}

body, .dashboard-bg {
    background: linear-gradient(135deg, var(--pastel-green), var(--soft-green));
    min-height: 100vh;
}

.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: 0.5rem;
    background-color: rgba(255,255,255,0.85);
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: .5rem;
    position: relative;
    width: 100%;
    transition: all 0.3s ease;
    border: 1px solid var(--pastel-green);
}

.info-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(152,216,170,0.15);
}

.info-box-icon {
    border-radius: 0.5rem;
    align-items: center;
    display: flex;
    font-size: 2rem;
    justify-content: center;
    text-align: center;
    width: 70px;
    color: #fff;
    background: linear-gradient(135deg, var(--pastel-green), var(--dark-green));
    box-shadow: 0 2px 8px rgba(152,216,170,0.15);
}

.info-box-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.8;
    flex: 1;
    padding: 0 10px;
}

.info-box-text {
    font-size: 1rem;
    color: var(--dark-green);
    font-weight: 600;
}

.info-box-number {
    font-weight: 700;
    font-size: 1.5rem;
    color: #343a40;
}

.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    background: rgba(255,255,255,0.92);
    border: 1px solid var(--light-cream);
}

.card-header {
    background: var(--light-cream);
    border-bottom: 1px solid var(--pastel-green);
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}

.card-title {
    color: var(--dark-green);
    font-weight: 600;
}

.table {
    margin-bottom: 0;
}

.table td, .table th {
    padding: 0.75rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
    background-color: var(--soft-green);
}

.badge-success {
    background: var(--pastel-green);
    color: var(--dark-green);
}

.badge-warning {
    background: var(--warm-cream);
    color: var(--dark-green);
}

.btn-primary, .btn-success, .btn-warning, .btn-info {
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary {
    background: var(--dark-green);
    color: #fff;
}
.btn-primary:hover {
    background: #3a7a5f;
}
.btn-success {
    background: var(--pastel-green);
    color: var(--dark-green);
}
.btn-success:hover {
    background: #b6eac7;
}
.btn-warning {
    background: var(--warm-cream);
    color: var(--dark-green);
}
.btn-warning:hover {
    background: #ffe2c2;
}
.btn-info {
    background: var(--soft-green);
    color: var(--dark-green);
}
.btn-info:hover {
    background: #b6eac7;
}

.quick-actions .btn {
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .info-box {
        min-height: 70px;
    }
    
    .info-box-icon {
        width: 60px;
        font-size: 1.5rem;
    }
    
    .info-box-number {
        font-size: 1.2rem;
    }
}
</style> 