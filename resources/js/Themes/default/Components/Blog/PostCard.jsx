import React from 'react'
import { Link } from '@inertiajs/react'

export default function PostCard({ post }) {
    const formatDate = (date) => {
        return new Date(date).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        })
    }

    return (
        <Link
            href={`/posts/${post.slug}`}
            className="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden flex flex-col"
        >
            {/* Featured Image */}
            <div className="relative w-full h-48 bg-gray-200 overflow-hidden">
                {post.cover ? (
                    <img
                        src={post.cover.path}
                        alt={post.title}
                        className="w-full h-full object-cover hover:scale-105 transition transform duration-300"
                    />
                ) : (
                    <div className="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                        <svg className="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                )}
            </div>

            {/* Content */}
            <div className="p-4 flex-1 flex flex-col">
                {/* Category Badge */}
                {post.category && (
                    <div className="mb-2">
                        <span className="inline-block px-2 py-1 text-xs font-medium text-white bg-[var(--primary-color)] rounded">
                            {post.category.name}
                        </span>
                    </div>
                )}

                {/* Title */}
                <h3 className="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-[var(--primary-color)] transition">
                    {post.title}
                </h3>

                {/* Excerpt */}
                <p className="text-sm text-gray-600 mb-4 line-clamp-2 flex-1">
                    {post.excerpt || 'Tidak ada deskripsi'}
                </p>

                {/* Meta */}
                <div className="flex items-center justify-between text-xs text-gray-500">
                    <span>{formatDate(post.published_at)}</span>
                    {post.tags && post.tags.length > 0 && (
                        <span className="flex items-center gap-1">
                            <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            {post.tags.length}
                        </span>
                    )}
                </div>
            </div>
        </Link>
    )
}
