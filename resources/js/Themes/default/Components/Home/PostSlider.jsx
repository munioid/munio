import React from 'react'
import { Link } from '@inertiajs/react'

export default function PostSlider({ posts, primaryColor }) {
    if (!posts || posts.length === 0) {
        return null
    }

    const placeholderImage = 'https://picsum.photos/500/300?1'

    const formatDate = (dateString) => {
        const date = new Date(dateString)
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }
        return date.toLocaleDateString('id-ID', options)
    }

    return (
        <section className="mt-3 bg-white py-6">
            <div className="flex justify-between items-center px-5 mb-5">
                <h2 className="text-2xl font-semibold">Berita Terkini</h2>
                <Link href="/posts" className="font-medium" style={{ color: primaryColor }}>
                    Selengkapnya →
                </Link>
            </div>

            <div className="flex gap-4 overflow-x-auto px-5 pb-2 scroll-smooth">
                {posts.map((post) => (
                    <Link
                        key={post.id}
                        href={`/posts/${post.slug}`}
                        className="min-w-[275px] bg-white border rounded-2xl overflow-hidden hover:shadow-lg transition-shadow"
                    >
                        <img
                            src={post.cover?.path || placeholderImage}
                            className="h-45 w-full object-cover"
                            alt={post.title}
                        />

                        <div className="p-5">
                            <div className="uppercase text-xs font-semibold" style={{ color: primaryColor }}>
                                {post.category?.name}
                            </div>
                            <h3 className="mt-2 text-2xl font-semibold leading-tight line-clamp-2">
                                {post.title}
                            </h3>
                            <p className="mt-3 text-sm text-gray-600 leading-6 line-clamp-3">
                                {post.excerpt}
                            </p>
                            <p className="text-gray-300 mt-4">{formatDate(post.published_at)}</p>
                        </div>
                    </Link>
                ))}
            </div>
        </section>
    )
}
