<?php
// Handle post actions
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'create':
            $title = sanitize($_POST['title']);
            $content = $_POST['content'];
            $category_id = (int)$_POST['category_id'];
            $status = sanitize($_POST['status']);
            
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, category_id, user_id, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$title, $content, $category_id, $_SESSION['user_id'], $status]);
            break;
            
        case 'update':
            $id = (int)$_POST['id'];
            $title = sanitize($_POST['title']);
            $content = $_POST['content'];
            $category_id = (int)$_POST['category_id'];
            $status = sanitize($_POST['status']);
            
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, category_id = ?, status = ? WHERE id = ?");
            $stmt->execute([$title, $content, $category_id, $status, $id]);
            break;
            
        case 'delete':
            $id = (int)$_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$id]);
            break;
    }
    
    header("Location: index.php?page=posts");
    exit();
}

// Get all posts
$posts = getAllPosts($pdo);
$categories = getAllCategories($pdo);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Posts</h1>
                </div>
                <div class="col-sm-6">
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createPostModal">
                        <i class="fas fa-plus"></i> New Post
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($post['username']); ?></td>
                                <td>
                                    <?php if ($post['status'] == 'published'): ?>
                                        <span class="badge badge-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editPostModal<?php echo $post['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deletePostModal<?php echo $post['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Post Modal -->
                            <div class="modal fade" id="editPostModal<?php echo $post['id']; ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Post</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="" method="post">
                                            <div class="modal-body">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                                                
                                                <div class="form-group">
                                                    <label>Title</label>
                                                    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>Content</label>
                                                    <textarea class="form-control" name="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select class="form-control" name="category_id" required>
                                                        <?php foreach ($categories as $category): ?>
                                                            <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $post['category_id'] ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($category['name']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" name="status" required>
                                                        <option value="published" <?php echo $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                                                        <option value="draft" <?php echo $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Post Modal -->
                            <div class="modal fade" id="deletePostModal<?php echo $post['id']; ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Post</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this post?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="" method="post">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Post</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Content</label>
                        <textarea class="form-control" name="content" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" name="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status" required>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Post</button>
                </div>
            </form>
        </div>
    </div>
</div> 