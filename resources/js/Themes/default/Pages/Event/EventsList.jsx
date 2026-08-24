import React, { useState, useEffect } from 'react'
import { usePage } from '@inertiajs/react'
import AppLayout from '../../Layouts/AppLayout'
import Filter from '../../Components/Event/Filter'
import EventCard from '../../Components/Event/EventCard'
import { LoadMore } from '../../Components/Partial'

export default function EventsList() {
    const { props } = usePage()
    const { events, categories, filters } = props
    const [isLoadingMore, setIsLoadingMore] = useState(false)
    const [displayedEvents, setDisplayedEvents] = useState(events.data)
    const [currentPage, setCurrentPage] = useState(events.current_page)
    const [lastPage, setLastPage] = useState(events.last_page)

    // Reset displayed events when filters change
    useEffect(() => {
        setDisplayedEvents(events.data)
        setCurrentPage(events.current_page)
        setLastPage(events.last_page)
    }, [events.data, events.current_page, events.last_page])

    const handleLoadMore = async () => {
        if (currentPage >= lastPage) return

        setIsLoadingMore(true)

        try {
            // Build parameters for next page
            const params = new URLSearchParams()
            if (filters?.category) params.append('category', filters.category)
            if (filters?.search) params.append('search', filters.search)
            params.append('page', currentPage + 1)

            // Fetch from API endpoint without changing URL
            const response = await fetch(`/events/api/load-more?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                }
            })

            if (!response.ok) throw new Error('Failed to load more events')

            const data = await response.json()

            setDisplayedEvents(prev => [...prev, ...data.data])
            setCurrentPage(data.current_page)
            setLastPage(data.last_page)
            setIsLoadingMore(false)
        } catch (error) {
            console.error('Error loading more events:', error)
            setIsLoadingMore(false)
        }
    }

    return (
        <AppLayout>
            <div className="min-h-screen bg-gray-50 pb-4">
                {/* Filter Section */}
                <Filter
                    categories={categories}
                    filters={filters}
                />

                {/* Events List */}
                <div className="space-y-5 py-4">
                    {displayedEvents.length > 0 ? (
                        displayedEvents.map(event => (
                            <EventCard key={event.id} event={event} />
                        ))
                    ) : (
                        <div className="py-12 text-center">
                            <p className="text-gray-600 text-lg">
                                Tidak ada event yang ditemukan
                            </p>
                        </div>
                    )}

                    {/* Load More Button */}
                    {currentPage < lastPage && (
                        <LoadMore
                            onClick={handleLoadMore}
                            loading={isLoadingMore}
                        />
                    )}
                </div>
            </div>
        </AppLayout>
    )
}
