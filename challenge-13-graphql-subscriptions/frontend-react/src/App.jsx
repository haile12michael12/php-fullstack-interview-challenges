import React, { useState, useEffect } from 'react';
import { ApolloProvider, useQuery, useMutation } from '@apollo/client';
import client, { queries, mutations } from './services/graphqlService';
import PostList from './components/PostList';
import PostForm from './components/PostForm';
import CommentSection from './components/CommentSection';
import RealTimeFeed from './components/RealTimeFeed';
import './App.css';

const AppContent = () => {
  const [selectedPost, setSelectedPost] = useState(null);
  const [showCreateForm, setShowCreateForm] = useState(false);
  
  // Fetch all posts
  const { data: postsData, loading: postsLoading, error: postsError, refetch } = useQuery(queries.GET_POSTS);
  
  // Create post mutation
  const [createPost, { loading: createPostLoading }] = useMutation(mutations.CREATE_POST, {
    onCompleted: () => {
      refetch();
      setShowCreateForm(false);
    }
  });
  
  // Create comment mutation
  const [createComment, { loading: createCommentLoading }] = useMutation(mutations.CREATE_COMMENT, {
    onCompleted: () => {
      refetch();
    }
  });
  
  // Handle post creation
  const handleCreatePost = (postData) => {
    createPost({ variables: postData });
  };
  
  // Handle comment creation
  const handleAddComment = (commentData) => {
    createComment({ variables: commentData });
  };
  
  // Handle post selection
  const handlePostClick = (post) => {
    setSelectedPost(post);
  };
  
  // Handle back to posts list
  const handleBackToList = () => {
    setSelectedPost(null);
  };

  return (
    <div className="App">
      <header className="App-header">
        <h1>GraphQL Blog with Real-Time Subscriptions</h1>
      </header>
      
      <main className="App-main">
        <div className="main-content">
          {!selectedPost ? (
            <div className="posts-view">
              <div className="posts-header">
                <h2>All Posts</h2>
                <button onClick={() => setShowCreateForm(!showCreateForm)}>
                  {showCreateForm ? 'Cancel' : 'Create Post'}
                </button>
              </div>
              
              {showCreateForm && (
                <PostForm onSubmit={handleCreatePost} />
              )}
              
              {postsLoading ? (
                <p>Loading posts...</p>
              ) : postsError ? (
                <p>Error loading posts: {postsError.message}</p>
              ) : (
                <PostList posts={postsData?.posts || []} onPostClick={handlePostClick} />
              )}
            </div>
          ) : (
            <div className="post-detail-view">
              <button onClick={handleBackToList} className="back-button">
                ← Back to Posts
              </button>
              
              <div className="post-detail">
                <h2>{selectedPost.title}</h2>
                <p>{selectedPost.content}</p>
                <div className="post-meta">
                  <span>By {selectedPost.author?.name || 'Unknown'}</span>
                  <span>Created: {new Date(selectedPost.createdAt).toLocaleDateString()}</span>
                </div>
                
                <CommentSection 
                  comments={selectedPost.comments || []}
                  postId={selectedPost.id}
                  onAddComment={handleAddComment}
                />
              </div>
            </div>
          )}
        </div>
        
        <div className="sidebar">
          <RealTimeFeed />
        </div>
      </main>
    </div>
  );
};

const App = () => {
  return (
    <ApolloProvider client={client}>
      <AppContent />
    </ApolloProvider>
  );
};

export default App;