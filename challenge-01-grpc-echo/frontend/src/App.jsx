import React from "react";
import EchoRest from "./components/EchoRest";
import EchoGrpc from "./components/EchoGrpc";

export default function App() {
  return (
    <div style={{ fontFamily: 'sans-serif', padding: 20 }}>
      <h1>PHP + gRPC Echo (Starter)</h1>
      <div style={{ display: 'flex', gap: 20 }}>
        <EchoRest />
        <EchoGrpc />
      </div>
    </div>
  );
}
