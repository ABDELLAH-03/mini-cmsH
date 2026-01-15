<?

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Page;

class PageEditor extends Component
{
    public $page;
    public $content;
    public $activeSection = null;
    public $isSaving = false;

    protected $listeners = ['sectionUpdated', 'sectionDeleted', 'addSection'];

    public function mount(Page $page)
    {
        $this->page = $page;
        $this->content = $page->content ?? ['sections' => []];
    }

    public function addSection($type, $templateId = null)
    {
        $sectionId = uniqid();

        $defaultSections = [
            'hero' => [
                'title' => 'Hero Title',
                'subtitle' => 'Hero Subtitle',
                'button_text' => 'Learn More',
                'button_link' => '#'
            ],
            'content' => [
                'html' => '<p>Your content here...</p>'
            ],
            'features' => [
                'items' => [
                    ['title' => 'Feature 1', 'description' => 'Description 1'],
                    ['title' => 'Feature 2', 'description' => 'Description 2']
                ]
            ]
        ];

        $this->content['sections'][] = [
            'id' => $sectionId,
            'type' => $type,
            'content' => $defaultSections[$type] ?? [],
            'settings' => []
        ];

        $this->activeSection = $sectionId;
        $this->save();
    }

    public function sectionUpdated($sectionId, $content)
    {
        foreach ($this->content['sections'] as &$section) {
            if ($section['id'] === $sectionId) {
                $section['content'] = $content;
                break;
            }
        }

        $this->save();
    }

    public function sectionDeleted($sectionId)
    {
        $this->content['sections'] = array_filter(
            $this->content['sections'],
            fn($section) => $section['id'] !== $sectionId
        );

        $this->save();
    }

    public function save()
    {
        $this->isSaving = true;

        $this->page->update([
            'content' => $this->content
        ]);

        $this->isSaving = false;
        $this->dispatchBrowserEvent('content-saved');
    }

    public function render()
    {
        return view('livewire.page-editor');
    }
}