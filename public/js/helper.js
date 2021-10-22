function AjaxHelper(contextPath) {
    this.contextPath = contextPath;
}

AjaxHelper.prototype.csrf_header = function() {
    var csrfName = $('meta[name="csrf-header"]').attr('content');
    var csrfContent = $('meta[name="csrf-token"]').attr('content');
    var header = {};
    header[csrfName] = csrfContent;
    return header;
};
AjaxHelper.prototype.get = function(relativePath, data, callback) { this.doMethod(relativePath, 'GET', data, callback); };
AjaxHelper.prototype.put = function(relativePath, data, callback) { this.doMethod(relativePath, 'PUT', data, callback); };
AjaxHelper.prototype.post = function(relativePath, data, callback) { this.doMethod(relativePath, 'POST', data, callback); };
AjaxHelper.prototype.patch = function(relativePath, data, callback) { this.doMethod(relativePath, 'PATCH', data, callback); };
AjaxHelper.prototype.delete = function(relativePath, data, callback) { this.doMethod(relativePath, 'DELETE', data, callback); };

AjaxHelper.prototype.doMethod = function(relativePath, method, data, callback) {
    if (typeof(data) === 'function') {
        callback = data;
        data = null;
    }

    let options = {
        url: this.contextPath + relativePath,
        dataType: 'json',
        method: method,
        timeout: 5000,
        success: function (response) {
            callback(null, response);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            callback('error');
        },
        statusCode: {
            403: function() {
                callback(403);
            },
            404: function() {
                callback(404);
            }
        }
    };

    if (method != 'GET') {
        options['headers'] = this.csrf_header();

        if (data != null) {
            options['data'] = JSON.stringify(data);
            options['contentType'] = "application/json; charset=utf-8";
            console.log(options['data']);
        }
    } else if (data != null) {
        options['data'] = data;
    }

    $.ajax(options);
};

