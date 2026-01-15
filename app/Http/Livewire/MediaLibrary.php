<?

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaLibrary extends Component
{
    use WithFileUploads;

    public $site;
    public $uploads = [];
    public $media = [];
    public $selected = [];
    public $isUploading = false;

    public function mount($site = null)
    {
        $this->site = $site;
        $this->loadMedia();
    }

    public function loadMedia()
    {
        $query = Media::where('user_id', auth()->id());

        if ($this->site) {
            $query->where('site_id', $this->site->id);
        }

        $this->media = $query->latest()->get();
    }

    public function upload()
    {
        $this->validate([
            'uploads.*' => 'image|max:5120'
        ]);

        $this->isUploading = true;

        foreach ($this->uploads as $upload) {
            $path = $upload->store('media/' . date('Y/m'), 'public');

            Media::create([
                'user_id' => auth()->id(),
                'site_id' => $this->site->id ?? null,
                'filename' => $upload->getClientOriginalName(),
                'path' => $path,
                'type' => 'image',
                'mime_type' => $upload->getMimeType(),
                'size' => $upload->getSize()
            ]);
        }

        $this->uploads = [];
        $this->isUploading = false;
        $this->loadMedia();
        $this->dispatchBrowserEvent('media-uploaded');
    }

    public function deleteSelected()
    {
        Media::whereIn('id', $this->selected)
            ->where('user_id', auth()->id())
            ->delete();

        $this->selected = [];
        $this->loadMedia();
    }

    public function render()
    {
        return view('livewire.media-library');
    }
}