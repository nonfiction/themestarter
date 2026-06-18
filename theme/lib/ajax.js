import jQuery from 'jquery';

function getAjaxUrl() {
  return globalThis.ajaxurl || '/wp-admin/admin-ajax.php';
}

function normalizeData(data = {}, callback = function() {}) {
  if (typeof data === 'function') {
    return [{}, data];
  }

  if (typeof data === 'number' || typeof data === 'string' || Array.isArray(data)) {
    return [{ data }, callback];
  }

  return [data, callback];
}

export function ajax(action, data = {}, callback = function() {}) {
  const [requestData, success] = normalizeData(data, callback);

  return jQuery.ajax({
    type: 'POST',
    url: getAjaxUrl(),
    data: { action, ...requestData },
    success,
  });
}
