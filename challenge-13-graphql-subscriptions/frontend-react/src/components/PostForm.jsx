import React, { useState } from 'react';

const PostForm = ({ onSubmit, initialData = {} }) => {
  const [title, setTitle] = useState(initialData.title || '');
  const [content, setContent] = useState(initialData.content || '');
  const [authorId, setAuthorId] = useState(initialData.authorId || '');

  const handleSubmit = (e) => {
    e.preventDefault();
    if (title && content && authorId) {
      onSubmit({ title, content, authorId });
    }
  };

  return (
    <div className="post-form">
      <h2>{initialData.id ? 'Edit Post' : 'Create New Post'}</h2>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="title">Title:</label>
          <input
            type="text"
            id="title"
            value={title}
            onChange={(e) => setTitle(e.target.value)}
            required
          />
        </div>
        
        <div className="form-group">
          <label htmlFor="content">Content:</label>
          <textarea
            id="content"
            value={content}
            onChange={(e) => setContent(e.target.value)}
            required
          />
        </div>
        
        <div className="form-group">
          <label htmlFor="authorId">Author ID:</label>
          <input
            type="text"
            id="authorId"
            value={authorId}
            onChange={(e) => setAuthorId(e.target.value)}
            required
          />
        </div>
        
        <button type="submit">Submit</button>
      </form>
    </div>
  );
};

export default PostForm;