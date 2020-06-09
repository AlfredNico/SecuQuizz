<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Competence;
use App\Entity\Competences;
use App\Entity\Compteur;
use App\Entity\Families;
use App\Entity\Niveau;
use App\Entity\Questions;
use App\Entity\Types;
use App\Entity\Users;
use App\Form\ExcelFormatType;
use App\Form\ImportFormType;
use App\Form\QuestionType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\CssSelector\Parser\Reader;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ExportImportController extends AbstractController
{

    protected function createSpreadsheet()
    {
        $spreadsheet = new Spreadsheet();
        // Get active sheet - it is also possible to retrieve a specific sheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Titre : Export_questions')->mergeCells('A1:D1');

        // Set column names
        $columnNames = [
            ' Titre question ',
            ' Article ',
            ' Competence ',
            ' Auteur ',
            ' Test compelemenaire ',
            ' Autre teste ',
            ' Etat ',
            ' Type ',
            ' Réponse '

        ];
        // 'Type'
        $columnLetter = 'A';
        foreach ($columnNames as $columnName) {
            // Allow to access AA column if needed and more
            $sheet->setCellValue($columnLetter . '3', $columnName);
            // Center text
            $sheet->getStyle($columnLetter . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            // Text in bold
            $sheet->getStyle($columnLetter . '3')->getFont()->setItalic(true);
            $sheet->getStyle($columnLetter . '3')->getFont()->setBold(true);
            $sheet->getStyle($columnLetter . '3')->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));
            // Autosize column
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            $columnLetter++;
        }
        // $question->getTypes() 
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findAll();
        $etat = 'Non valider';
        $valider = 'faux';
        $i = 4; // Beginning row for active sheet
        foreach ($questions as $question) {
            // $competence = $this->getDoctrine()->getRepository(Competences::class)->findOneBy(['id' => $question->getCompetences()]);
            // if ($competence!=null) {
            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(["id" => $question->getUsers()]);
            $article = $this->getDoctrine()->getRepository(Families::class)->findOneBy(["id" => $question->getArticle()]);
            $competence = $this->getDoctrine()->getRepository(Competence::class)->findOneBy(["article" => $article->getId()]);
            $answer = $this->getDoctrine()->getRepository(Answers::class)->findOneBy(["questions" => $question->getId()]);

            if ($answer->getisAnswer() ==  1) {
                $valider = 'vrai';
            }

            if ($question->getEtat() != null && $question->getEtat() == 1) {
                # code...
                $etat = 'Valider';
            };

            // $familie = $this->getDoctrine()->getRepository(Families::class)->findOneBy(['id' => $competence->getFamilies()]);
            // if ($familie!=null) {
            $ColumnValue = [
                // $familie->getTitle(),  $competence->getTitle(),
                $question->getTitle(), $article->getTitle(), $competence->getTitle(), $question->getTexteComplementaire(),
                $user->getEmail(), $question->getAutreTexte(), $etat,  $question->getType(), $answer->getTitle() . '/ ' . $valider
            ];

            $columnLetter = 'A';
            foreach ($ColumnValue as $value) {
                $sheet->setCellValue($columnLetter . $i, $value);
                $columnLetter++;
            }

            $i++;
            // }
            // }

        }
        return $spreadsheet;
    }

    /**
     * @Route("/export_excel", name="export_excel")
     */
    public function exportAction(Request $request)
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $this->getUser() 


        $form = $this->createForm(ExcelFormatType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $format = $data['format'];
            $filename = 'Export_Liste_questions.' . $format;

            $spreadsheet = $this->createSpreadsheet();

            switch ($format) {
                case 'ods':
                    $contentType = 'application/vnd.oasis.opendocument.spreadsheet';
                    $writer = new Ods($spreadsheet);
                    break;
                case 'xlsx':
                    $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $writer = new Xlsx($spreadsheet);
                    break;
                case 'csv':
                    $contentType = 'text/csv';
                    $writer = new Csv($spreadsheet);
                    break;
                default:
                    return $this->render('export_import/export.html.twig', [
                        'form' => $form->createView(),
                    ]);
            }

            # dd($filename);

            $response = new StreamedResponse();
            $response->headers->set('Content-Type', $contentType);
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
            $response->setPrivate();
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCallback(function () use ($writer) {
                $writer->save('php://output');
            });

            return $response;
        }

        return $this->render('export_import/export.html.twig', [
            'controller_name' => 'ExportImportController',
            'form' => $form->createView(),
        ]);
    }


    protected function loadFile($filename)
    {
        return IOFactory::load($filename);
    }

    protected function readFile($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'ods':
                $reader = new ReaderOds();
                break;
            case 'xlsx':
                $reader = new ReaderXlsx();
                break;
            case 'csv':
                $reader = new ReaderCsv();
                break;
            default:
                throw new \Exception('Invalid extension');
        }
        $reader->setReadDataOnly(true);
        return $reader->load($filename);
    }

    protected function createDataFromSpreadsheet($spreadsheet, UserInterface $users, $parent, $article)
    {
        $data = [];
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $data[$worksheetTitle] = [
                'columnNames' => [],
                'columnValues' => [],
            ];
            foreach ($worksheet->getRowIterator() as $row) {
                $rowIndex = $row->getRowIndex();
                if ($rowIndex > 1) {
                    $data[$worksheetTitle]['columnValues'][$rowIndex] = [];
                }
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop over all cells, even if it is not set
                foreach ($cellIterator as $cell) {
                    if ($rowIndex === 1) {
                        $data[$worksheetTitle]['columnNames'][] = $cell->getCalculatedValue();
                    }
                    if ($rowIndex > 1) {
                        $data[$worksheetTitle]['columnValues'][$rowIndex][] = $cell->getCalculatedValue();
                    }
                }
            }
        }
        $values = $data[$worksheetTitle]['columnValues'];
        //dd($values);

        $vas = array();
        foreach ($values as $va) {
            # code...
            $vas[] = array(
                'Question' => $va[0],
                // 'Article' => $va[1],
                'Competence' => $va[1],
                // 'Niveau' => $va[3],
                'Texte_complementaire' => $va[2],
                'Autre_Texte' => $va[3],
                'Type' => $va[4],
                'Reponse' => $va[5],
                'isAnswer' => $va[6]
            );
        }



        $user = $this->getUser();
        $compteur = $this->getDoctrine()->getRepository(Compteur::class)->findOneBy(['id' => 1]);
        $lig = 1;

        //dd($vas);

        $em = $this->getDoctrine()->getManager();
        foreach ($vas as $r) {
            // $niveau = $this->getDoctrine()->getRepository(Niveau::class)->findOneBy(['title' => $r['Niveau']]);
            $type = $this->getDoctrine()->getRepository(Types::class)->findOneBy(['title' => $r['Type']]);
            $arti = $this->getDoctrine()->getRepository(Families::class)->findOneBy(['id' => $article]);
            $compt = $this->getDoctrine()->getRepository(Competence::class)->findOneBy(['article' => $article]);
            $question_ques = $this->getDoctrine()->getRepository(Questions::class)->findOneBy(['title' => $r['Question']]);

            if ($question_ques != null) {
                $numc_question = $question_ques->getNumc();
                if ($type != null && $arti != null && $compt != null) {

                    $reps = $this->getDoctrine()->getRepository(Answers::class)->findBy(['numc' => $numc_question]);
                    // $ques_answers = $question_ques->getAnswers();
                    // dd($ques_answers);
                    $max_lig = 0;
                    foreach ($reps as $rep) {
                        if ($max_lig < $rep->getLig()) {
                            $max_lig = $rep->getLig();
                        }
                        // dd($max_lig);
                    }
                    // dd($max_lig);
                    #Reponse

                    // $max_lig = $max_lig + 1;

                    $reponse = new Answers();
                    $reponse->setTitle($r['Reponse']);
                    $reponse->setIsAnswer($r['isAnswer']);
                    $reponse->setNumc($numc_question);
                    $reponse->setLig($max_lig + 1);
                    $reponse->setQuestions($question_ques);
                    $em->persist($reponse);

                    $em->flush();

                    // dd($r);
                }
            } else {
                if ($type != null && $arti != null && $compt != null) {
                    # Question
                    $question = new Questions();
                    $question->setTitle($r['Question']);
                    $question->setTexteComplementaire($r['Texte_complementaire']);
                    $question->setAutreTexte($r['Autre_Texte']);
                    $question->setEtat('à valider');
                    $question->setNumc($compteur->getNumcom());
                    $question->setType($type);
                    $question->setUsers($user);
                    $question->setArticle($arti);
                    $em->persist($question);

                    #Reponse
                    $reponse = new Answers();
                    $reponse->setTitle($r['Reponse']);
                    $reponse->setIsAnswer($r['isAnswer']);
                    $reponse->setNumc($compteur->getNumcom());
                    $reponse->setLig($lig);
                    $reponse->setQuestions($question);
                    $em->persist($reponse);

                    $compteur->setNumcom($compteur->getNumcom() + 1);
                    $em->persist($compteur);

                    $em->flush();

                    // dd($r);
                }
            }
        }
        return $data;
    }

    /**
     * @Route("/import_excel/{parent}/{article}", name="import_excel")
     */
    public function importAction(Request $request, SluggerInterface $slugger, UserInterface $users = null, $parent, $article)
    {
        $form = $this->createForm(ImportFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form->get('import')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                //$newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                $newFilename = $safeFilename . '-' . uniqid() . '.' . 'csv';
                //dd($newFilename);
                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        //$this->getParameter('brochures_directory'),
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }


            // $filename = $this->getParameter('kernel.project_dir') . $filename_Line;
            // $filename = $this->getParameter('kernel.project_dir')  . '/public/export/Browser_characteristics.csv';
            $filename = $this->getParameter('kernel.project_dir')  . '/public/import/' . $newFilename;

            if (!file_exists($filename)) {
                throw new \Exception('File does not exist');
            }

            $spreadsheet = $this->readFile($filename);
            $data = $this->createDataFromSpreadsheet($spreadsheet, $users, $parent, $article);

            $this->addFlash('message', 'Import fichier excel a été effectuer avec succés!');

            // return $this->render('export_import/readimport.html.twig', [
            //     'data' => $data,
            // ]);
            return $this->redirectToRoute('questions_index', [
                'parent' => $parent,
                'article' => $article
            ]);
        }



        // //dd($data);
        return $this->render('export_import/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/export2", name="export2")
     */
    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Bonjours mes amies');


        return $this->render("export_import/testImport.html.twig");
    }
}
