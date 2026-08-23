import React from 'react'
import { usePage, Link } from '@inertiajs/react'
import AppLayout from '../../Layouts/AppLayout'
import DOMPurify from 'dompurify'

export default function PostDetail() {
    const { props } = usePage()
    const { post, relatedPosts } = props

    const sanitizeHtml = (html) => {
        return DOMPurify.sanitize(html)
    }

    const formatDate = (date) => {
        return new Date(date).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        })
    }

    const getImageUrl = (post) => {
        if (!post.cover) return null
        // Handle different attachment formats
        if (typeof post.cover === 'string') return post.cover
        return post.cover.path || post.cover.url || null
    }

    return (
        <AppLayout>
            <div className="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
                <div className="max-w-3xl mx-auto">
                    {/* Back Button */}
                    <Link
                        href="/posts"
                        className="inline-flex items-center text-[var(--primary-color)] hover:opacity-80 mb-8"
                    >
                        <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Blog
                    </Link>

                    {/* Post Content */}
                    <article className="bg-white rounded-lg shadow-lg overflow-hidden">
                        {/* Featured Image */}
                        {getImageUrl(post) && (
                            <div className="w-full h-96 bg-gray-200 overflow-hidden">
                                <img
                                    src={getImageUrl(post)}
                                    alt={post.title}
                                    className="w-full h-full object-cover"
                                    onError={(e) => {
                                        e.target.parentElement.style.display = 'none'
                                    }}
                                />
                            </div>
                        )}

                        <div className="p-8 md:p-12">
                            {/* Category Badge */}
                            {post.category && (
                                <div className="mb-4">
                                    <span className="inline-block px-3 py-1 text-sm font-medium text-white bg-[var(--primary-color)] rounded-full">
                                        {post.category.name}
                                    </span>
                                </div>
                            )}

                            {/* Title */}
                            <h1 className="text-4xl font-bold text-gray-900 mb-4">
                                {post.title}
                            </h1>

                            {/* Meta Information */}
                            <div className="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-8 pb-8 border-b border-gray-200">
                                <span>Diterbitkan pada {formatDate(post.published_at)}</span>
                                {post.source && (
                                    <span>Sumber: {post.source}</span>
                                )}
                            </div>

                            {/* Excerpt */}
                            {post.excerpt && (
                                <p className="text-lg text-gray-600 mb-8 italic">
                                    {post.excerpt}
                                </p>
                            )}

                            {/* Tags */}
                            {post.tags && post.tags.length > 0 && (
                                <div className="flex flex-wrap gap-2 mb-8">
                                    {post.tags.map(tag => (
                                        <Link
                                            key={tag.id}
                                            href={`/posts?tag=${tag.id}`}
                                            className="px-3 py-1 text-sm text-gray-700 bg-gray-200 rounded-full hover:bg-gray-300 transition"
                                        >
                                            #{tag.name}
                                        </Link>
                                    ))}
                                </div>
                            )}

                            {/* Post Content */}
                            <div
                                className="prose prose-sm md:prose max-w-none mb-8"
                                dangerouslySetInnerHTML={{
                                    __html: sanitizeHtml(post.content)
                                }}
                            />
                        </div>
                    </article>

                    {/* Related Posts */}
                    {relatedPosts && relatedPosts.length > 0 && (
                        <div className="mt-16">
                            <h2 className="text-2xl font-bold text-gray-900 mb-8">
                                Artikel Terkait
                            </h2>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                                {relatedPosts.map(relatedPost => (
                                    <Link
                                        key={relatedPost.id}
                                        href={`/posts/${relatedPost.slug}`}
                                        className="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden"
                                    >
                                        {getImageUrl(relatedPost) && (
                                            <div className="w-full h-48 bg-gray-200 overflow-hidden">
                                                <img
                                                    src={getImageUrl(relatedPost)}
                                                    alt={relatedPost.title}
                                                    className="w-full h-full object-cover hover:scale-105 transition transform"
                                                    onError={(e) => {
                                                        e.target.parentElement.style.display = 'none'
                                                    }}
                                                />
                                            </div>
                                        )}
                                        <div className="p-4">
                                            <h3 className="font-semibold text-gray-900 line-clamp-2">
                                                {relatedPost.title}
                                            </h3>
                                            <p className="text-sm text-gray-600 mt-2 line-clamp-2">
                                                {relatedPost.excerpt}
                                            </p>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    )
}
