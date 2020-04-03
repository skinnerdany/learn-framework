<?php

class posts extends model
{
    public function savePost($postData = [])
    {
        $id = (int) ($postData['id'] ?? 0);
        unset($postData['id']);
        if ($id == 0) {
            self::$db->insert('posts', $postData);
        } else {
            self::$db->update('posts', $postData, ['id' => $id]);
        }
    }
    
    public function getPosts($id = false)
    {
        $posts = [];
        if ($id !== false) {
            $posts = self::$db->select('posts', '*', ['id' => $id]);
            $posts = !count($posts) ? [] : reset($posts);
        } else {
            $posts = self::$db->select('posts', '*');
        }
        return $posts;
    }
    
    public function deletePost(int $id)
    {
        self::$db->delete('posts', ['id' => $id]);
    }
}
