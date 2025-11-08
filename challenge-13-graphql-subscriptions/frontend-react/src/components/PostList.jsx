import React from 'react';

const PostList = ({ posts, onPostClick }) => {
  if (!posts || posts.length === 0) {
    return <div className="post-list">No posts found.</div>;
  }

  return (
    <div className="post-list">
      <h2>Posts</h2>
      {posts.map(post => (
        <div key={post.id} className="post-item" onClick={() => onPostClick && onPostClick(post)}>
          <h3>{post.title}</h3>
          <p>{post.content.substring(0, 100)}...</p>
          <div className="post-meta">
            <span>By {post.author?.name || 'Unknown'}</span>
            <span>Created: {new Date(post.createdAt).toLocaleDateString()}</span>
          </div>
        </div>
      ))}
    </div>
  );
};

export default PostList;