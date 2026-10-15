<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(15);
        $nonLus = Message::where('lu', false)->count();
        return view('admin.messages.index', compact('messages', 'nonLus'));
    }

    public function show(Message $message)
    {
        if (!$message->lu) {
            $message->update(['lu' => true, 'lu_le' => now()]);
        }
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')
            ->with('success', 'Message supprimé.');
    }

    /**
     * Action groupée : delete | mark_read | mark_unread.
     */
    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:delete,mark_read,mark_unread'],
            'ids'    => ['required', 'string'],
        ]);

        $ids = $this->parseIds($validated['ids']);

        if (empty($ids)) {
            return back()->with('error', 'Aucun message sélectionné.');
        }

        switch ($validated['action']) {
            case 'delete':
                $count = Message::whereIn('id', $ids)->delete();
                $msg   = $count . ' message(s) supprimé(s).';
                break;

            case 'mark_read':
                $count = Message::whereIn('id', $ids)
                    ->update(['lu' => true, 'lu_le' => now()]);
                $msg = $count . ' message(s) marqué(s) comme lu(s).';
                break;

            case 'mark_unread':
            default:
                $count = Message::whereIn('id', $ids)
                    ->update(['lu' => false, 'lu_le' => null]);
                $msg = $count . ' message(s) marqué(s) comme non lu(s).';
                break;
        }

        return back()->with('success', $msg);
    }

    /**
     * "1,2,3" → [1, 2, 3] (ints positifs uniques).
     */
    protected function parseIds(string $csv): array
    {
        return array_values(array_unique(array_filter(
            array_map('intval', explode(',', $csv)),
            fn ($id) => $id > 0
        )));
    }
}