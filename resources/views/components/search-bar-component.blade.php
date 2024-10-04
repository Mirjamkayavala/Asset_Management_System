<div style="display: flex; justify-content: flex-end; padding: 10px;">
    <div class="search-bar">
        <form action="{{ route('assets.search') }}" method="GET">
            <input type="text" id="searchInput" name="query" placeholder="Search...">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>
