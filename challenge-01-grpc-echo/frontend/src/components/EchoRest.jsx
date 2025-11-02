import React, { useState } from 'react';

export default function EchoRest() {
  const [text, setText] = useState('');
  const [reply, setReply] = useState('');

  const send = async () => {
    try {
      const res = await fetch('http://localhost:8080/echo', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text })
      });
      const data = await res.json();
      setReply(data.text || JSON.stringify(data));
    } catch (e) {
      setReply('Error: ' + e.message);
    }
  };

  return (
    <div style={{ padding: 12, border: '1px solid #ddd', borderRadius: 8, width: 360 }}>
      <h3>REST Echo (quick test)</h3>
      <input value={text} onChange={e => setText(e.target.value)} placeholder="Type message" style={{ width: '100%', padding: 8 }} />
      <button onClick={send} style={{ marginTop: 8, width: '100%', padding: 8 }}>Send (REST)</button>
      <div style={{ marginTop: 12 }}><strong>Reply:</strong> {reply}</div>
    </div>
  );
}
