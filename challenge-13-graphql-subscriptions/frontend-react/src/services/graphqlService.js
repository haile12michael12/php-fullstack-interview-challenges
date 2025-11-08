import { ApolloClient, InMemoryCache, HttpLink, split, ApolloProvider } from '@apollo/client';
import { GraphQLWsLink } from '@apollo/client/link/subscriptions';
import { createClient } from 'graphql-ws';
import { getMainDefinition } from '@apollo/client/utilities';

// HTTP link for queries and mutations
const httpLink = new HttpLink({
  uri: 'http://localhost:8080/graphql',
});

// WebSocket link for subscriptions
const wsLink = new GraphQLWsLink(createClient({
  url: 'ws://localhost:8081/graphql',
}));

// Split links based on operation type
const splitLink = split(
  ({ query }) => {
    const definition = getMainDefinition(query);
    return (
      definition.kind === 'OperationDefinition' &&
      definition.operation === 'subscription'
    );
  },
  wsLink,
  httpLink,
);

// Create Apollo Client
const client = new ApolloClient({
  link: splitLink,
  cache: new InMemoryCache(),
});

// GraphQL queries
export const queries = {
  GET_USERS: `
    query GetUsers {
      users {
        id
        name
        email
        createdAt
      }
    }
  `,
  
  GET_POSTS: `
    query GetPosts {
      posts {
        id
        title
        content
        author {
          id
          name
        }
        createdAt
      }
    }
  `,
  
  GET_POST: `
    query GetPost($id: ID!) {
      post(id: $id) {
        id
        title
        content
        author {
          id
          name
        }
        comments {
          id
          content
          author {
            id
            name
          }
          createdAt
        }
        createdAt
      }
    }
  `,
  
  SEARCH_POSTS: `
    query SearchPosts($query: String!) {
      searchPosts(query: $query) {
        id
        title
        content
        author {
          id
          name
        }
        createdAt
      }
    }
  `
};

// GraphQL mutations
export const mutations = {
  CREATE_USER: `
    mutation CreateUser($name: String!, $email: String!, $password: String!) {
      createUser(name: $name, email: $email, password: $password) {
        id
        name
        email
        createdAt
      }
    }
  `,
  
  CREATE_POST: `
    mutation CreatePost($title: String!, $content: String!, $authorId: ID!) {
      createPost(title: $title, content: $content, authorId: $authorId) {
        id
        title
        content
        createdAt
      }
    }
  `,
  
  CREATE_COMMENT: `
    mutation CreateComment($content: String!, $authorId: ID!, $postId: ID!) {
      createComment(content: $content, authorId: $authorId, postId: $postId) {
        id
        content
        createdAt
      }
    }
  `,
  
  UPDATE_USER: `
    mutation UpdateUser($id: ID!, $name: String, $email: String) {
      updateUser(id: $id, name: $name, email: $email) {
        id
        name
        email
        updatedAt
      }
    }
  `,
  
  UPDATE_POST: `
    mutation UpdatePost($id: ID!, $title: String, $content: String) {
      updatePost(id: $id, title: $title, content: $content) {
        id
        title
        content
        updatedAt
      }
    }
  `,
  
  DELETE_POST: `
    mutation DeletePost($id: ID!) {
      deletePost(id: $id)
    }
  `
};

// GraphQL subscriptions
export const subscriptions = {
  POST_ADDED: `
    subscription PostAdded {
      postAdded {
        id
        title
        content
        author {
          id
          name
        }
        createdAt
      }
    }
  `,
  
  COMMENT_ADDED: `
    subscription CommentAdded($postId: ID) {
      commentAdded(postId: $postId) {
        id
        content
        author {
          id
          name
        }
        post {
          id
        }
        createdAt
      }
    }
  `
};

export default client;