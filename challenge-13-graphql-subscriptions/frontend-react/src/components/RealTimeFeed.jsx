import React, { useEffect, useState } from 'react';
import { useSubscription } from '@apollo/client';
import { subscriptions } from '../services/graphqlService';

const RealTimeFeed = () => {
  const [feedItems, setFeedItems] = useState([]);
  
  // Subscribe to new posts
  const {
    data: postData,
    loading: postLoading,
    error: postError
  } = useSubscription(subscriptions.POST_ADDED);
  
  // Subscribe to new comments
  const {
    data: commentData,
    loading: commentLoading,
    error: commentError
  } = useSubscription(subscriptions.COMMENT_ADDED);

  // Handle new posts
  useEffect(() => {
    if (postData?.postAdded) {
      setFeedItems(prevItems => [
        { type: 'post', data: postData.postAdded, timestamp: new Date() },
        ...prevItems
      ]);
    }
  }, [postData]);

  // Handle new comments
  useEffect(() => {
    if (commentData?.commentAdded) {
      setFeedItems(prevItems => [
        { type: 'comment', data: commentData.commentAdded, timestamp: new Date() },
        ...prevItems
      ]);
    }
  }, [commentData]);

  if (postLoading || commentLoading) {
    return <div className="real-time-feed">Loading real-time feed...</div>;
  }

  if (postError || commentError) {
    return <div className="real-time-feed">Error loading real-time feed.</div>;
  }

  return (
    <div className="real-time-feed">
      <h2>Real-Time Feed</h2>
      {feedItems.length === 0 ? (
        <p>No real-time updates yet.</p>
      ) : (
        <div className="feed-items">
          {feedItems.map((item, index) => (
            <div key={index} className={`feed-item ${item.type}`}>
              <div className="feed-item-header">
                <span className="feed-item-type">{item.type.toUpperCase()}</span>
                <span className="feed-item-time">
                  {item.timestamp.toLocaleTimeString()}
                </span>
              </div>
              {item.type === 'post' ? (
                <div className="feed-item-content">
                  <h4>{item.data.title}</h4>
                  <p>{item.data.content.substring(0, 100)}...</p>
                  <div className="feed-item-meta">
                    <span>By {item.data.author?.name || 'Unknown'}</span>
                  </div>
                </div>
              ) : (
                <div className="feed-item-content">
                  <p>{item.data.content}</p>
                  <div className="feed-item-meta">
                    <span>By {item.data.author?.name || 'Unknown'}</span>
                    <span>On post: {item.data.post?.id || 'Unknown'}</span>
                  </div>
                </div>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default RealTimeFeed;