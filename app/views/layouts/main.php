<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text\html;charset=utf-8" />
        <script src="/js/jquery.js"></script>
        <script>
            $(function () {
                loadPosts();
            });
            function loadPosts()
            {
                $.ajax({
                    url: '/?controller=posts',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        var html = '';
                        for (var i in response) {
                            html += '<a href="#" onclick="getPost(' + response[i]['id'] + ');">' + response[i]['title'] + '</a>&nbsp;&nbsp;&nbsp;<a href="#" onclick="deletePost('+response[i]['id']+');">X</a><br />';
                        }
                        $('#postsList').html(html);
                    }
                });
            }
            function getPost(id)
            {
                $.ajax({
                    url: '/?controller=posts&id=' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (r) {
                        $('input[name=id]').val(r.id);
                        $('input[name=title]').val(r.title);
                        $('textarea[name=text]').val(r.text);
                    }
                });
            }
            function deletePost(id)
            {
                $.ajax({
                    url: '/?controller=posts&id=' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function (r) {
                        if (r.success == 1) {
                            loadPosts();
                        } else {
                            $('#error').html('Ошибка удаления поста');
                        }
                    }
                });
            }
            function savePost()
            {
                var id = +$('input[name=id]').val();
                var method = 'POST';
                var data = {
                    id: id,
                    title: $('input[name=title]').val(),
                    text: $('textarea[name=text]').val()
                };
                if (id != 0) {
                    method = 'PUT';
                }
                $.ajax ({
                    url: '/?controller=posts',
                    dataType: 'json',
                    data: data,
                    type: method,
                    success: function (r) {
                        if (r.success == 1) {
                            loadPosts();
                            $('input[name=id]').val(0);
                            $('input[name=title]').val('');
                            $('textarea[name=text]').val('');
                        } else {
                            $('#error').html('Ошибка сохранения поста');
                        }
                    }
                });
            }
        </script>
    </head>
    <body>
        <div id="error" style="color: #f00;"></div>
        <div id="postsList"></div>
        <form id="postsEditor">
            <input type="hidden" name="id" value="0" />
            <input type="text" name="title" value="" /><br />
            <textarea name="text"></textarea><br />
            <input type="button" value="Сохранить" onclick="savePost();">
        </form>
    </body>
</html>