import React, { useState, useEffect } from 'react'
import { usePage, router } from '@inertiajs/react'
import AppLayout from '../../Layouts/AppLayout'
import Filter from '../../Components/Blog/Filter'
import PostCard from '../../Components/Blog/PostCard'

export default function PostsList() {
    const { props, url } = usePage()
    const { posts, categories, tags, filters } = props
    const [isLoadingMore, setIsLoadingMore] = useState(false)
    const [displayedPosts, setDisplayedPosts] = useState(posts.data)
    const [currentPage, setCurrentPage] = useState(posts.current_page)
    const [lastPage, setLastPage] = useState(posts.last_page)

    // Reset displayed posts when filters change
    useEffect(() => {
        setDisplayedPosts(posts.data)
        setCurrentPage(posts.current_page)
        setLastPage(posts.last_page)
    }, [posts.data, posts.current_page, posts.last_page])

    const handleLoadMore = async () => {
        if (currentPage >= lastPage) return

        setIsLoadingMore(true)

        try {
            // Build parameters for next page
            const params = new URLSearchParams()
            if (filters?.category) params.append('category', filters.category)
            if (filters?.tag) params.append('tag', filters.tag)
            if (filters?.search) params.append('search', filters.search)
            params.append('page', currentPage + 1)

            // Fetch from API endpoint without changing URL
            const response = await fetch(`/posts/api/load-more?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                }
            })

            if (!response.ok) throw new Error('Failed to load more posts')

            const data = await response.json()

            setDisplayedPosts(prev => [...prev, ...data.data])
            setCurrentPage(data.current_page)
            setLastPage(data.last_page)
            setIsLoadingMore(false)
        } catch (error) {
            console.error('Error loading more posts:', error)
            setIsLoadingMore(false)
        }
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
                    {currentPage < lastPage && (
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
