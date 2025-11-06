export const highlightSyntax = (code, language = 'javascript') => {
  // Simple syntax highlighting implementation
  // In a real application, you would use a library like Prism.js or Highlight.js
  
  switch (language) {
    case 'javascript':
      return code
        .replace(/(\/\/.*)/g, '<span class="comment">$1</span>')
        .replace(/('.*?')/g, '<span class="string">$1</span>')
        .replace(/(".*?")/g, '<span class="string">$1</span>')
        .replace(/\b(function|return|const|let|var|if|else|for|while|class|extends)\b/g, '<span class="keyword">$1</span>');
    case 'html':
      return code
        .replace(/(&lt;!--.*?--&gt;)/g, '<span class="comment">$1</span>')
        .replace(/(&lt;\/?[a-z][a-z0-9]*\b[^&]*&gt;)/gi, '<span class="tag">$1</span>');
    default:
      return code;
  }
};

export const highlightCodeBlocks = (html) => {
  // Find code blocks and apply syntax highlighting
  return html.replace(/<pre><code class="language-(\w+)">([^<]+)<\/code><\/pre>/g, (match, language, code) => {
    const unescapedCode = code.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&');
    const highlightedCode = highlightSyntax(unescapedCode, language);
    return `<pre><code class="language-${language}">${highlightedCode}</code></pre>`;
  });
};