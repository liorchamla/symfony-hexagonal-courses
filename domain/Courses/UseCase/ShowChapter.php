<?php

namespace Domain\Courses\UseCase;


use Domain\Authentication\Gateway\UserRepositoryInterface;
use Domain\Courses\Exception\ChapterNotFoundException;
use Domain\Courses\Gateway\ChapterRepositoryInterface;
use Domain\Courses\UseCase\RequestModel\ShowChapterRequest;
use Domain\Courses\UseCase\ViewModel\ShowChapterViewModel;

class ShowChapter
{
    protected ChapterRepositoryInterface $chapterRepository;
    protected UserRepositoryInterface $userRepository;

    public function __construct(ChapterRepositoryInterface $chapterRepository, UserRepositoryInterface $userRepository)
    {
        $this->chapterRepository = $chapterRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(ShowChapterRequest $request): ShowChapterViewModel
    {
        $chapter = $this->chapterRepository->findOneOrNull($request->uuid);

        if (null === $chapter) {
            throw new ChapterNotFoundException(sprintf(
                'Chapter with UUID %s was not found',
                $request->uuid
            ));
        }

        $canPlay = $request->userUuid ? $this->userRepository->hasUserBoughtThisCourse($request->userUuid, $chapter->course->uuid) : false;

        return new ShowChapterViewModel($chapter, $canPlay);
    }
}
