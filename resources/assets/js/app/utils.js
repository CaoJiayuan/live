/**
 * Created by d on 17-5-27.
 */
// export function uploadFile(e, url, onSuccess, onError) {
//   let el                           = e.target,
//       name = el.name || 'file', id = el.id;
//       file = el.files[0];
//   onSuccess = onSuccess || function () {};
//   onError = onError || function () {};
//   let fd   = new FormData();
//   fd.append(name, file);
//   var xhr  = new XMLHttpRequest();
//   xhr.open('POST', url);
//   xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
//   xhr.setRequestHeader("X-CSRF-TOKEN", window.Laravel.csrfToken);
//   xhr.onreadystatechange = function () {
//     if (xhr.readyState == 4) {
//       var responseText = xhr.responseText;
//       try {
//         responseText = JSON.parse(xhr.responseText);
//       } catch (e) {}
//       if (xhr.status == 200) {
//         onSuccess(responseText)
//       } else {
//         onError(responseText)
//       }
//     }
//   };
//   xhr.send(fd);
// }
export function uploadFile(e, url, onSuccess, onError) {
  let el   = e.target,
      name = el.name || 'file';
  if (typeof onSuccess != 'function') {
    onSuccess = function () {
      };
  }
  if (typeof onError != 'function') {
    onError = function () {
      };
  }

  var files = FileAPI.getFiles(e);
  FileAPI.filterFiles(files, function (file, info) {
    return /^image/.test(file.type);
  }, function (files, rejected) {
    if (rejected.length) {
      var error = {
        message: '文件必须为图片!'
      };
      onError(error);
      return;
    }
    if (files.length) {
      FileAPI.upload({
        url: url,
        files: { file: files[0] },
        progress: function (evt){ /* ... */ },
        complete: function (err, xhr){
          window.console.log(xhr.responseText);
          if (err) {
            return;
          }
          var json = xhr.responseText;

          try {
            json = JSON.parse(xhr.responseText);
          } catch (e) {
          }
          onSuccess.call(null, json)
        }
      });
    }
  });
}
export function toastrNotification(type, message, overloadOptions) {
  // type有4种，success、info、warning、error
  toastr.options = {
    "debug"            : false,
    "newestOnTop"      : false,
    "positionClass"    : "toast-top-center",
    "preventDuplicates": true,
    "onclick"          : null,
    "showDuration"     : "300",
    "hideDuration"     : "1000",
    "timeOut"          : "3000",
    "extendedTimeOut"  : "1000",
    "showEasing"       : "swing",
    "hideEasing"       : "linear",
    "showMethod"       : "fadeIn",
    "hideMethod"       : "fadeOut"
  };

  if (typeof overloadOptions == 'object') {
    for (var i in overloadOptions) {
      toastr.options[i] = overloadOptions[i];
    }
  }

  return toastr[type](message);
}

/**
 *
 * @param {String} url
 * @param {Object} query
 * @returns {string}
 */
export function setQuery(url, query) {
  let obj = parseUrl(url);
  let q   = obj.query;

  for (let i in  query) {
    var item = query[i];
    if (_.isString(item)) {
      item.length > 0 && (q[i] = item);
    } else {
      q[i] = item;
    }
  }

  return obj.domain + '?' + httpQueryString(q);
}

/**
 *
 * @param {String} url
 * @returns {Object}
 */
export function parseUrl(url) {
  let part = url.split('?', 2);
  if (part.length < 2) {
    return {
      domain     : part[0],
      queryString: '',
      query      : {}
    }
  }
  let qs    = part[1].split('&');
  let query = {};
  qs.forEach(function (item) {
    let p = item.split('=');
    if (p.length > 1) {
      query[p[0]] = p[1];
    }
  });

  return {
    domain     : part[0],
    queryString: part[1],
    query      : query
  }
}
/**
 *
 * @param {Object} query
 * @returns {string}
 */
export function httpQueryString(query) {
  let qs = '';
  for (let i in query) {
    qs += '&' + i + '=' + query[i];
  }
  return qs.length >= 1 ? qs.substr(1) : qs;
}

/**
 *
 * @param {string} s
 * @returns {string}
 */
export function htmlencode(s) {
  var div = document.createElement('div');
  div.appendChild(document.createTextNode(s));
  return div.innerHTML;
}