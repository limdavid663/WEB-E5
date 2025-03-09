<template>
    <div>
        <h1>{{ post.title }}</h1>
        <div>{{ post.content }}</div>

        <div class="mt-4">
            <Link :href="`/posts/${post.id}/edit`" class="btn">Edit</Link>
            <Link href="/posts" class="btn">Back to Posts</Link>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link
    },
    props: {
        post: Object
    }
};
</script>
