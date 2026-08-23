import React from 'react'
import { Link } from '@inertiajs/react'

export default function PostCard({ post }) {
    const formatDate = (date) => {
        return new Date(date).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        })
    }

    const getImageUrl = (post) => {
        if (!post.cover) {
            return 'https://picsum.photos/240/240?1'
        }
        // Handle different attachment formats
        if (typeof post.cover === 'string') return post.cover
        return post.cover.path || post.cover.url || 'https://picsum.photos/240/240?1'
    }

    return (
        <Link
            href={`/posts/${post.slug}`}
            className="flex gap-4 rounded-2xl bg-white p-3 shadow-sm"
        >
            {/* Featured Image */}
            <img
                src={getImageUrl(post)}
                alt={post.title}
                className="h-28 w-28 rounded-xl object-cover"
                onError={(e) => {
                    e.target.src = 'https://picsum.photos/240/240?1'
                }}
            />

            {/* Content */}
            <div className="flex flex-1 flex-col">
                {/* Category Badge */}
                {post.category && (
                    <div className="text-xs font-semibold uppercase text-primary">
                        {post.category.name}
                    </div>
                )}

                {/* Title */}
                <h3 className="mt-1 line-clamp-2 text-lg font-semibold">
                    {post.title}
                </h3>

                {/* Excerpt */}
                <p className="mt-2 line-clamp-2 text-sm text-gray-500">
                    {post.excerpt}
                </p>

                {/* Meta */}
                <span className="mt-auto pt-3 text-xs text-gray-400">
                    {formatDate(post.published_at)}
                </span>
            </div>
        </Link>
    )
}
