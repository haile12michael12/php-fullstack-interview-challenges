import React, { useState } from 'react';

const CommentSection = ({ comments, postId, onAddComment }) => {
  const [newComment, setNewComment] = useState('');
  const [authorId, setAuthorId] = useState('');

  const handleAddComment = (e) => {
    e.preventDefault();
    if (newComment && authorId && postId) {
      onAddComment({ content: newComment, authorId, postId });
      setNewComment('');
    }
  };

  return (
    <div className="comment-section">
      <h3>Comments</h3>
      
      {comments && comments.length > 0 ? (
        <div className="comments-list">
          {comments.map(comment => (
            <div key={comment.id} className="comment-item">
              <p>{comment.content}</p>
              <div className="comment-meta">
                <span>By {comment.author?.name || 'Unknown'}</span>
                <span>Created: {new Date(comment.createdAt).toLocaleDateString()}</span>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <p>No comments yet.</p>
      )}
      
      <div className="add-comment">
        <h4>Add a Comment</h4>
        <form onSubmit={handleAddComment}>
          <div className="form-group">
            <label htmlFor="comment-content">Comment:</label>
            <textarea
              id="comment-content"
              value={newComment}
              onChange={(e) => setNewComment(e.target.value)}
              required
            />
          </div>
          
          <div className="form-group">
            <label htmlFor="comment-authorId">Your ID:</label>
            <input
              type="text"
              id="comment-authorId"
              value={authorId}
              onChange={(e) => setAuthorId(e.target.value)}
              required
            />
          </div>
          
          <button type="submit">Add Comment</button>
        </form>
      </div>
    </div>
  );
};

export default CommentSection;