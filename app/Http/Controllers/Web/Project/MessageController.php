<?php

namespace Arbify\Http\Controllers\Web\Project;

use Arbify\Http\Controllers\BaseController;
use Arbify\Contracts\Repositories\MessageRepository;
use Arbify\Contracts\Repositories\MessageValueRepository;
use Arbify\Contracts\Repositories\ProjectRepository;
use Arbify\Http\Requests\StoreMessage;
use Arbify\Http\Resources\Language as LanguageResource;
use Arbify\Http\Resources\Message as MessageResource;
use Arbify\Models\Message;
use Arbify\Models\Project;
use Gate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends BaseController
{
    private ProjectRepository $projectRepository;
    private MessageRepository $messageRepository;
    private MessageValueRepository $messageValueRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        MessageRepository $messageRepository,
        MessageValueRepository $messageValueRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->messageRepository = $messageRepository;
        $this->messageValueRepository = $messageValueRepository;

        $this->middleware('verified');
    }

    public function index(Project $project): View
    {
        $this->authorize('view-any', [Message::class, $project]);

        return view('projects.messages', ['project' => $project]);
    }

    public function indexData(Project $project): array
    {
        $this->authorize('view-any', [Message::class, $project]);

        $languages = $project->languages;
        $messages = $project->messages;
        $values = $this->messageValueRepository->allByProject($project);

        return [
            'languages' => LanguageResource::collection($languages),
            'messages' => MessageResource::collection($messages),
            'values' => $values,
            'can_create_messages' => Gate::allows('create', [Message::class, $project]),
        ];
    }

    public function store(StoreMessage $request, Project $project): MessageResource
    {
        $this->authorize('create', [Message::class, $project]);

        $message = Message::create([
                'project_id' => $project->id,
            ] + $request->validated());

        return new MessageResource($message);
    }

    public function update(StoreMessage $request, Project $project, Message $message): MessageResource
    {
        $this->authorize('update', [$message, $project]);

        $message->update($request->validated());

        return new MessageResource($message);
    }

    public function destroy(Project $project, Message $message): Response
    {
        $this->authorize('delete', [$message, $project]);

        $message->delete();

        return status(Response::HTTP_NO_CONTENT);
    }
}
