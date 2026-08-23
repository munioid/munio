import React, { useState } from 'react'
import { usePage, router } from '@inertiajs/react'
import AppLayout from '../../Layouts/AppLayout'
import Filter from '../../Components/Blog/Filter'
import PostCard from '../../Components/Blog/PostCard'

export default function PostsList() {
    const { props, url } = usePage()
    const { posts, categories, tags, filters } = props
    const [isLoadingMore, setIsLoadingMore] = useState(false)
    const [displayedPosts, setDisplayedPosts] = useState(posts.data)

    const handleLoadMore = () => {
        if (!posts.next_page_url) return

        setIsLoadingMore(true)

        // Extract page number from next_page_url
        const urlParams = new URLSearchParams(new URL(posts.next_page_url, window.location.origin).search)
        const nextPage = urlParams.get('page')

        // Build parameters
        const params = new URLSearchParams()
        if (filters?.category) params.append('category', filters.category)
        if (filters?.tag) params.append('tag', filters.tag)
        if (filters?.search) params.append('search', filters.search)
        params.append('page', nextPage)

        router.visit(`/posts?${params.toString()}`, {
            preserveScroll: true,
            onSuccess: (page) => {
                const newPosts = page.props.posts.data
                setDisplayedPosts(prev => [...prev, ...newPosts])
                setIsLoadingMore(false)
            },
            onError: () => setIsLoadingMore(false),
        })
    }

    return (
        <AppLayout>
            <div className="min-h-screen bg-gray-50 pb-4">
                {/* Filter Section */}
                <Filter
                    categories={categories}
                    tags={tags}
                    filters={filters}
                />

                {/* Posts List */}
                <div className="space-y-5 py-4">
                    {displayedPosts.length > 0 ? (
                        displayedPosts.map(post => (
                            <PostCard key={post.id} post={post} />
                        ))
                    ) : (
                        <div className="px-5 py-12 text-center">
                            <p className="text-gray-600 text-lg">
                                Tidak ada post yang ditemukan
                            </p>
                        </div>
                    )}

                    {/* Load More Button */}
                    {posts.current_page < posts.last_page && (
                        <div className="px-5 py-4">
                            <button
                                onClick={handleLoadMore}
                                disabled={isLoadingMore}
                                className="w-full rounded-xl border border-primary py-3 text-primary font-medium hover:bg-primary hover:text-white transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {isLoadingMore ? 'Memuat...' : 'Muat Lebih Banyak'}
                            </button>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    )
}
