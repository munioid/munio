import React, { useState } from 'react'
import { usePage, Link } from '@inertiajs/react'
import AppLayout from '../../Layouts/AppLayout'
import Filter from '../../Components/Blog/Filter'
import PostCard from '../../Components/Blog/PostCard'

export default function PostsList() {
    const { props } = usePage()
    const { posts, categories, tags, filters } = props
    const [selectedFilters, setSelectedFilters] = useState({
        category: filters?.category || null,
        tag: filters?.tag || null,
        search: filters?.search || '',
    })

    const handleFilterChange = (filterType, value) => {
        setSelectedFilters(prev => ({
            ...prev,
            [filterType]: value
        }))
    }

    const handleSearch = (value) => {
        handleFilterChange('search', value)
    }

    const getFilterUrl = () => {
        const params = new URLSearchParams()
        if (selectedFilters.category) params.append('category', selectedFilters.category)
        if (selectedFilters.tag) params.append('tag', selectedFilters.tag)
        if (selectedFilters.search) params.append('search', selectedFilters.search)
        return `/posts${params.toString() ? '?' + params.toString() : ''}`
    }

    return (
        <AppLayout>
            <div className="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
                <div className="max-w-7xl mx-auto">
                    {/* Header */}
                    <div className="mb-12">
                        <h1 className="text-4xl font-bold text-gray-900 mb-4">Blog</h1>
                        <p className="text-lg text-gray-600">
                            Jelajahi artikel dan berita terbaru dari kami
                        </p>
                    </div>

                    {/* Filter Section */}
                    <div className="mb-12">
                        <Filter
                            categories={categories}
                            tags={tags}
                            filters={selectedFilters}
                            onFilterChange={handleFilterChange}
                            onSearch={handleSearch}
                        />
                    </div>

                    {/* Posts Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                        {posts.data.length > 0 ? (
                            posts.data.map(post => (
                                <PostCard key={post.id} post={post} />
                            ))
                        ) : (
                            <div className="col-span-full py-12 text-center">
                                <p className="text-gray-600 text-lg">
                                    Tidak ada post yang ditemukan
                                </p>
                            </div>
                        )}
                    </div>

                    {/* Pagination */}
                    {posts.last_page > 1 && (
                        <div className="flex justify-center items-center gap-2">
                            {posts.links.map((link, index) => (
                                <Link
                                    key={index}
                                    href={link.url || '#'}
                                    className={`px-4 py-2 rounded-lg transition ${
                                        link.active
                                            ? 'bg-[var(--primary-color)] text-white'
                                            : link.url
                                                ? 'bg-gray-200 text-gray-800 hover:bg-gray-300'
                                                : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    )
}
