<form action="{{ route('emprunts.store') }}" method="POST">
    @csrf
    <input type="hidden" name="livre_id" value="{{ $livre->id }}">

    <button type="submit"
        class="w-full bg-green-500 hover:bg-green-600 text-white py-1 rounded text-xs">
        Emprunter
    </button>
</form>
