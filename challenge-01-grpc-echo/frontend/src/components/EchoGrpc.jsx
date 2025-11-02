import React, { useState } from 'react';

/*
  This component shows a placeholder for grpc-web client usage.
  The included project ships the proto and generated folder placeholders.
  If you generate grpc-web JS bindings and run Envoy proxy, you can switch this
  to use the grpc-web client generated code.

  For now it falls back to the REST endpoint so the frontend works out-of-the-box.
*/

export default function EchoGrpc() {
  const [text, setText] = useState('');
  const [reply, setReply] = useState('');

  const send = async () => {
    // Fallback: use REST until grpc-web is set up
    try {
      const res = await fetch('http://localhost:8080/echo', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text })
      });
      const data = await res.json();
      setReply('(fallback REST) ' + data.text);
    } catch (e) {
      setReply('Error: ' + e.message);
    }
  };

  return (
    <div style={{ padding: 12, border: '1px solid #ddd', borderRadius: 8, width: 360 }}>
      <h3>gRPC Echo (placeholder)</h3>
      <input value={text} onChange={e => setText(e.target.value)} placeholder="Type message" style={{ width: '100%', padding: 8 }} />
      <button onClick={send} style={{ marginTop: 8, width: '100%', padding: 8 }}>Send (gRPC)</button>
      <div style={{ marginTop: 12 }}><strong>Reply:</strong> {reply}</div>
    </div>
  );
}
