export const sanitizeHtml = (html) => {
  // Remove script tags
  let sanitized = html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
  
  // Remove on* attributes
  sanitized = sanitized.replace(/\s*on\w+\s*=\s*["\'][^"\']*["\']/gi, '');
  
  // Allow only safe tags
  const allowedTags = /<(?!\/?(h1|h2|h3|h4|h5|h6|p|br|strong|em|u|ol|ul|li|a|img)\b)[^>]*>/gi;
  sanitized = sanitized.replace(allowedTags, '');
  
  return sanitized;
};

export const escapeHtml = (text) => {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  
  return text.replace(/[&<>"']/g, (m) => map[m]);
};