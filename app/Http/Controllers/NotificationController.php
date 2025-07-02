<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BorrowRequest;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ManualNotification;

class NotificationController extends Controller
{
    const DEFAULT_AVATAR = '/default-avatar.png';

    public function index(Request $request)
    {
        $notifications = $this->getFilteredNotifications($request);
        $users = User::where('role', 'user')->get();

        if ($request->ajax()) {
            return response()->json($this->formatNotifications($notifications));
        }

        return view('notifications.index', compact('notifications', 'users'));
    }

    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsReadIfUnread();

        return response()->json($this->buildNotificationData($notification));
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    }

    public function create()
    {
        return view('notifications.send', [
            'users' => User::where('active', true)->get()
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'title' => 'required',
            'message' => 'required',
        ]);

        $users = $this->getNotificationRecipients($validated['user_id']);
        $this->sendManualNotifications($users, $validated);

        return redirect()->route('notifications.index')
            ->with('success', 'Notifikasi berhasil dikirim.');
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count()
        ]);
    }

    private function getFilteredNotifications(Request $request)
    {
        $query = Auth::user()->notifications();

        if ($request->status === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($request->status === 'unread') {
            $query->whereNull('read_at');
        }

        if ($request->search) {
            $query->where('data->title', 'like', '%' . $request->search . '%');
        }

        return $query->latest()->get();
    }

    private function formatNotifications($notifications)
    {
        return $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'Notifikasi',
                'message' => $notification->data['message'] ?? '-',
                'created_at' => $notification->created_at->format('d M Y H:i'),
                'read_at' => $notification->read_at,
            ];
        });
    }

    private function buildNotificationData($notification)
    {
        $data = [
            'id' => $notification->id,
            'title' => $notification->data['title'] ?? 'Notifikasi',
            'message' => $notification->data['message'] ?? '-',
            'created_at' => $notification->created_at->format('d M Y H:i'),
            'related_type' => null,
            'related_id' => null,
            'related_url' => null,
            'details' => [],
            'user' => null,
        ];

        if (isset($notification->data['borrow_request_id'])) {
            $this->addBorrowRequestData($data, $notification->data['borrow_request_id']);
        } elseif (isset($notification->data['return_request_id'])) {
            $this->addReturnRequestData($data, $notification->data['return_request_id']);
        }

        return $data;
    }

    private function addBorrowRequestData(&$data, $borrowRequestId)
    {
        $borrow = BorrowRequest::with(['borrowDetail.itemUnit.item', 'user'])
            ->find($borrowRequestId);

        if (!$borrow)
            return;

        $data['related_type'] = 'Peminjaman';
        $data['related_id'] = $borrow->id;
        $data['related_url'] = route('borrow-requests.show', $borrow->id);
        $data['details'] = $this->mapBorrowDetails($borrow->borrowDetail);
        $data['user'] = $this->mapUserData($borrow->user);
    }

    private function addReturnRequestData(&$data, $returnRequestId)
    {
        $return = ReturnRequest::with(['returnDetails.itemUnit.item', 'user'])
            ->find($returnRequestId);

        if (!$return)
            return;

        $data['related_type'] = 'Pengembalian';
        $data['related_id'] = $return->id;
        $data['related_url'] = route('return_requests.show', $return->id);
        $data['details'] = $this->mapReturnDetails($return->returnDetails);
        $data['user'] = $this->mapUserData($return->user);
    }

    private function mapBorrowDetails($borrowDetails)
    {
        return $borrowDetails->map(function ($detail) {
            return [
                'id' => $detail->id,
                'name' => $detail->itemUnit->item->name ?? '-',
                'image_url' => $detail->itemUnit->item->image_url ?? null,
                'quantity' => $detail->quantity,
            ];
        });
    }

    private function mapReturnDetails($returnDetails)
    {
        return $returnDetails->map(function ($detail) {
            return [
                'id' => $detail->id,
                'name' => $detail->itemUnit->item->name ?? '-',
                'image_url' => $detail->itemUnit->item->image_url ?? null,
            ];
        });
    }

    private function mapUserData($user)
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'profile_picture' => $user->profile_picture ?? self::DEFAULT_AVATAR,
        ];
    }

    private function getNotificationRecipients($userId)
    {
        return $userId === 'all'
            ? User::where('active', true)->get()
            : User::where('id', $userId)->get();
    }

    private function sendManualNotifications($users, $data)
    {
        Notification::send($users, new ManualNotification([
            'title' => $data['title'],
            'message' => $data['message'],
        ]));
    }
}
