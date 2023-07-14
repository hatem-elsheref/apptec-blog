<?php

namespace App\Services;

use App\Models\React;

class ReactService
{
    public function listingAllPostReacts($post) :array
    {
        $reacts = React::query()->with('user')->where('post_id', $post->id)->get();

        return [
            'reacts' => $reacts,
            'groups' => $reacts->groupBy('is_like')
        ];
    }
    public function deleteReact($react) :array
    {
        return $react->delete()
            ? ['type'    => 'success', 'message' => 'Deleted Successfully']
            : ['type'    => 'danger', 'message' => 'Failed To Update'];
    }
}

