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
            hour: '2-digit',
            minute: '2-digit',
        })
    }

    const getImageUrl = (post) => {
        if (!post.cover) {
            return 'https://picsum.photos/900/500'
        }
        // Handle different attachment formats
        if (typeof post.cover === 'string') return post.cover
        return post.cover.path || post.cover.url || 'https://picsum.photos/900/500'
    }

    return (
        <AppLayout title={post.title}>
            <div className="min-h-screen bg-gray-50 pb-7">
                {/* Cover Image */}
                <img
                    src={getImageUrl(post)}
                    alt={post.title}
                    className="aspect-[16/9] w-full object-cover"
                    onError={(e) => {
                        e.target.src = 'https://picsum.photos/900/500'
                    }}
                />

                {/* Content Container */}
                <div className="rounded-t-3xl bg-white -mt-6 relative z-10 px-5 pt-6">
                    {/* Category Badge */}
                    {post.category && (
                        <div className="text-xs font-semibold uppercase text-primary">
                            {post.category.name}
                        </div>
                    )}

                    {/* Title */}
                    <h1 className="mt-2 text-3xl font-bold leading-tight">
                        {post.title}
                    </h1>

                    {/* Meta Information */}
                    <div className="mt-4 flex items-center gap-2 text-sm text-gray-500">
                        <span>{formatDate(post.published_at)}</span>
                        {post.source && (
                            <>
                                <span>•</span>
                                <span>{post.source}</span>
                            </>
                        )}
                    </div>

                    {/* Tags */}
                    {post.tags && post.tags.length > 0 && (
                        <div className="mt-5 flex flex-wrap gap-2">
                            {post.tags.map(tag => (
                                <span key={tag.id} className="rounded-full bg-gray-100 px-3 py-1 text-xs">
                                    #{tag.name}
                                </span>
                            ))}
                        </div>
                    )}

                    {/* Post Content */}
                    <article
                        className="prose prose-gray mt-8 max-w-none prose-img:rounded-2xl prose-headings:font-bold prose-a:text-primary"
                        dangerouslySetInnerHTML={{
                            __html: sanitizeHtml(post.content)
                        }}
                    />

                    {/* Related Posts */}
                    {relatedPosts && relatedPosts.length > 0 && (
                        <div className="mt-16 border-t pt-8">
                            <h2 className="text-2xl font-semibold mb-8">
                                Artikel Terkait
                            </h2>
                            <div className="flex gap-4 overflow-x-auto pb-2">
                                {relatedPosts.map(relatedPost => (
                                    <Link
                                        key={relatedPost.id}
                                        href={`/posts/${relatedPost.slug}`}
                                        className="min-w-[275px] bg-white border rounded-2xl overflow-hidden"
                                    >
                                        <img
                                            src={relatedPost.cover ? (typeof relatedPost.cover === 'string' ? relatedPost.cover : (relatedPost.cover.path || relatedPost.cover.url || 'https://picsum.photos/500/300?1')) : 'https://picsum.photos/500/300?1'}
                                            alt={relatedPost.title}
                                            className="h-45 w-full object-cover"
                                            onError={(e) => {
                                                e.target.src = 'https://picsum.photos/500/300?1'
                                            }}
                                        />
                                        <div className="p-5">
                                            {relatedPost.category && (
                                                <div className="text-primary uppercase text-xs font-semibold">
                                                    {relatedPost.category.name}
                                                </div>
                                            )}
                                            <h3 className="mt-2 text-2xl font-semibold leading-tight line-clamp-2">
                                                {relatedPost.title}
                                            </h3>
                                            <p className="mt-3 text-sm text-gray-600 leading-6 line-clamp-3">
                                                {relatedPost.excerpt}
                                            </p>
                                            <p className="text-gray-300 mt-4">
                                                {new Date(relatedPost.published_at).toLocaleDateString('id-ID', {
                                                    year: 'numeric',
                                                    month: 'long',
                                                    day: 'numeric',
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                })}
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
